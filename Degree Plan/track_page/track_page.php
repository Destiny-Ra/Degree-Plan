<?php
session_start();

// get env variables from .env file
require '../vendor/autoload.php';
require '../includes/db_connection.php';

(Dotenv\Dotenv::createImmutable(__DIR__ . '/../'))->load();

if (!defined('ROUTE_URL_INDEX')) {
  define('ROUTE_URL_INDEX', rtrim($_ENV['AUTH0_BASE_URL'], '/'));
}
if (!defined('ROUTE_URL_LOGIN')) {
  define('ROUTE_URL_LOGIN', ROUTE_URL_INDEX . '/login');
}
if (!defined('ROUTE_URL_LOGOUT')) {
  define('ROUTE_URL_LOGOUT', ROUTE_URL_INDEX . '/logout');
}


// Check if the user is logged in; if not, redirect to the login route.
if (!isset($_SESSION['user'])) {
  header("Location: " . (defined('ROUTE_URL_LOGIN') ? ROUTE_URL_LOGIN : 'index.php'));
  exit;
}

// Retrieve the user details needed
$user = $_SESSION['user'];
$nickname = $user['nickname'];
$email = $user['email'];



// query for student creation or retrieval
// check if current student exists
$stmt = $conn->prepare("SELECT student_id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt-> execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $studentId = $row["student_id"];
} else {
  // if the student doesn't exist, create them
  $stmt->close();
  $stmt = $conn->prepare("INSERT INTO students (email) VALUES (?)");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $studentId = $conn->insert_id;
}
$stmt->close();

// add button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
  $courseIdToAdd = intval($_POST['course_id']);

  $semester = isset($_POST['semester']) ? $_POST['semester'] : 'SPRING';
  $year = isset($_POST['year']) ? intval($_POST['year']) : 2025;

  // check if student has a record of this course for matching values already
  $checkStmt = $conn->prepare("SELECT enrollment_id FROM student_courses WHERE student_id = ? AND course_id = ? AND semester = ? AND year = ?");
  $checkStmt->bind_param("iisi", $studentId, $courseIdToAdd, $semester, $year);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  if ($checkResult->num_rows == 0) {
    // insert a student-course record if there isn't one
    $insertStmt = $conn->prepare("INSERT INTO student_courses (student_id, course_id, semester, year) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("iisi", $studentId, $courseIdToAdd, $semester, $year);
    $insertStmt->execute();
    $insertStmt->close();

  
  }
  $checkStmt->close();
  // redirect to the same page to avoid duplicate submissions
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}



// fill the table with courses from the student_courses joint table or insert into it

$query =  "SELECT sc.enrollment_id, sc.course_id, c.course_code, c.title, c.description, c.credits, sc.semester, sc.year
FROM student_courses AS sc
JOIN courses AS c ON sc.course_id = c.course_id
WHERE sc.student_id = ? AND c.course_type = 'Comp Sci General' AND c.course_code LIKE 'COSC 4%'";

$courseStmt = $conn->prepare( $query );
$courseStmt->bind_param("i", $studentId);
$courseStmt->execute();
$currentCoursesResult = $courseStmt->get_result();
$courseStmt->close();

// if a user searches for a course, then show matching courses
$searchResults = null;
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
  $searchTerm = "%" . $_GET['search_query'] . "%";
  $searchStmt = $conn->prepare("SELECT course_id, course_code, title, description, credits, course_type
  FROM courses
  WHERE course_type = 'Comp Sci General' AND course_code LIKE 'COSC 4%' AND (course_code LIKE ? OR title LIKE ?)");
  $searchStmt->bind_param("ss", $searchTerm, $searchTerm);
  $searchStmt->execute();
  $searchResults = $searchStmt->get_result();
  $searchStmt->close();
}


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Computer Science Track- UTPB Degree Plan</title>
    <link rel="stylesheet" href="style.css" />
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const searchDiv = document.getElementById('searchFormDiv');
        // 1. See if they've toggled before
        const pref = localStorage.getItem('searchFormVisible');
        if (pref === 'true') {
          searchDiv.style.display = 'block';
        } else if (pref === 'false') {
          searchDiv.style.display = 'none';
        } else {
          // 2. No pref yet: open if there's a search_query in the URL
          const hasQuery = <?php echo isset($_GET['search_query']) ? 'true' : 'false'; ?>;
          searchDiv.style.display = hasQuery ? 'block' : 'none';
        }
    });

    function toggleSearchForm() {
      const searchDiv = document.getElementById('searchFormDiv');
      const isOpen = window.getComputedStyle(searchDiv).display !== 'none';
      // toggle display
      searchDiv.style.display = isOpen ? 'none' : 'block';
      // persist choice
      localStorage.setItem('searchFormVisible', !isOpen);
    }
    </script>

  </head>
  <body>
    <header class="site-header">
      <div class="logo-container">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/TEMP/55b36452908e385c22adcd0f6d3b59feb0ad23df"
          alt="UTPB ICON"
          class="logo-image"
        />
      </div>
    </header>

    <main class="main-content">
      <nav class="navigation-bar">
        <h1 class="page-title">Degree Plan</h1>
        <div class="nav-actions">
          <button class="nav-button" onclick="location.href='../home_page/home_page.php'">Back</button>
          <button class="nav-button">Logout</button>
        </div>
      </nav>

      <section class="content-section">
        <h2 class="student-greeting">Hi <?php echo htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8'); ?>,</h2>
        <div class="degree-plan-container">
          <h3 class="section-title">Computer Science Track</h3>

          <!-- Dynamic Course Table -->
          <div class="course-table">
            <div class="table-header">
              <div class="header-cell status-cell"></div>
              <div class="header-cell">Course Code</div>
              <div class="header-cell">Course Name</div>
              <div class="header-cell">Credits</div>
              <div class="header-cell">Semester</div>
              <div class="header-cell">Year</div>
            </div>
            <?php if ($currentCoursesResult->num_rows > 0): ?>
              <?php while($course = $currentCoursesResult->fetch_assoc()): ?>
                <div class="table-row">
                  <div class="cell status-cell">Course</div>
                  <div class="cell"><?php echo htmlspecialchars($course['course_code']); ?></div>
                  <div class="cell"><?php echo htmlspecialchars($course['title']); ?></div>
                  <div class="cell"><?php echo (int)$course['credits']; ?></div>
                  <div class="cell"><?php echo htmlspecialchars($course['semester']); ?></div>
                  <div class="cell"><?php echo (int)$course['year']; ?></div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="table-row">
                <div class="cell status-cell">-</div>
                <div class="cell" style="grid-column: span 3;">No Computer Science Track courses added yet.</div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Update Section with Plus Button -->
          <div class="update-section">
            <button class="update-button" onclick="toggleSearchForm()">+ Add Course</button>
          </div>

          <!-- Hidden Search Form for Adding Courses -->
          <div id="searchFormDiv" class="search-section">
            <!-- 1) Search box (GET) -->
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input 
                type="text" 
                name="search_query" 
                class="search-control search-control--inverse" 
                placeholder="Enter course title" 
                value="<?php echo htmlspecialchars($_GET['search_query'] ?? ''); ?>" 
                />
                <button type="submit" class="update-button">Search</button>
            </form>

            <?php if ($searchResults !== null && $searchResults->num_rows > 0): ?>
              <div class="course-table" style="margin-top:20px;">
                <div class="table-header">
                  <div class="header-cell">Course Number</div>
                  <div class="header-cell">Course</div>
                  <div class="header-cell">Credits</div>
                  <div class="header-cell">Semester</div>
                  <div class="header-cell">Year</div>
                  <div class="header-cell">Action</div>
                </div>

                <?php while($row = $searchResults->fetch_assoc()): ?>
                  <form 
                    method="POST" 
                    action="<?php echo $_SERVER['PHP_SELF']; ?>" 
                    class="table-row add-course-form"
                  >
                    <!-- hidden course_id -->
                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">

                    <!-- 1) Course Number -->
                    <div class="cell"><?php echo htmlspecialchars($row['course_code']); ?></div>
                    <!-- 2) Course Name -->
                    <div class="cell"><?php echo htmlspecialchars($row['title']); ?></div>
                    <!-- 3) Credits -->
                    <div class="cell"><?php echo (int)$row['credits']; ?></div>
                    <!-- 4) Semester picker -->
                    <div class="cell">
                      <select name="semester" class="search-control">
                        <option value="SPRING">SPRING</option>
                        <option value="SUMMER">SUMMER</option>
                        <option value="FALL">FALL</option>
                      </select>
                    </div>
                    <!-- 5) Year input -->
                    <div class="cell">
                      <input
                        type="number"
                        name="year"
                        class="search-control"
                        value="<?php echo date('Y'); ?>"
                        min="2000"
                        max="2100"
                      />
                    </div>
                    <!-- 6) Add button -->
                    <div class="cell">
                      <button type="submit" name="add_course" class="update-button">Add</button>
                    </div>
                  </form>
                <?php endwhile; ?>
              </div>
            <?php elseif ($searchResults !== null): ?>
              <p>No courses found matching your search.</p>
            <?php endif; ?>
        </div>
          <!-- Credit Summary (Static Display) -->
          <div class="credit-summary">
            <p class="summary-text">
              Credit Needed : ----<br />
              Total Credits Required: ?
            </p>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <p class="copyright-text">
        Copyright © 2024 The University of Texas Permian Basin
      </p>
    </footer>
  </body>
</html>