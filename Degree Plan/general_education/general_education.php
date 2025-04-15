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

$query =  "SELECT sc.enrollment_id, sc.course_id, c.course_code, c.title, c.description, sc.semester, sc.year
FROM student_courses sc
JOIN courses c ON sc.course_id = c.course_id
WHERE sc.course_id = ? AND c.course_type = 'General Ed'";

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
  WHERE course_type = 'General Ed' AND title LIKE ?");
  $searchStmt->bind_param("s", $searchTerm);
  $searchStmt->execute();
  $searchResults = $searchStmt->get_result();
  $searchStmt->close();
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>General Education Courses</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <style>
        /* Basic styling for the table and '+' button */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        #searchFormDiv {
            display: none; /* Hidden by default and toggled by the '+' button */
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .plus-btn {
            font-size: 24px;
            cursor: pointer;
        }
    </style>
    <script>
    // JavaScript to toggle the visibility of the search form.
    function toggleSearchForm() {
        var searchDiv = document.getElementById("searchFormDiv");
        if (searchDiv.style.display === "none") {
            searchDiv.style.display = "block";
        } else {
            searchDiv.style.display = "none";
        }
    }
    </script>
  </head>
<body>

<h1>General Education Courses</h1>

<!-- STEP 3: Display the student's current General Ed. courses -->
<?php if ($currentCoursesResult->num_rows > 0): ?>
    <table>
        <tr>
            <th>Course ID</th>
            <th>Course Code</th>
            <th>Title</th>
            <th>Description</th>
            <th>Semester</th>
            <th>Year</th>
        </tr>
        <?php while($course = $currentCoursesResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($course['course_id']); ?></td>
            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
            <td><?php echo htmlspecialchars($course['title']); ?></td>
            <td><?php echo htmlspecialchars($course['description']); ?></td>
            <td><?php echo htmlspecialchars($course['semester']); ?></td>
            <td><?php echo htmlspecialchars($course['year']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No general education courses added yet.</p>
<?php endif; ?>

<!-- The '+' button is always visible to allow adding courses -->
<button class="plus-btn" onclick="toggleSearchForm()">+ Add Course</button>

<!-- Hidden Search Form for Adding Courses -->
<div id="searchFormDiv">
    <h2>Search General Education Courses</h2>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search_query" placeholder="Enter course title" 
               value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
    
    <?php if ($searchResults !== null): ?>
        <?php if ($searchResults->num_rows > 0): ?>
            <h3>Search Results:</h3>
            <table>
                <tr>
                    <th>Course ID</th>
                    <th>Course Code</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $searchResults->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <!-- Each result comes with its own form to add the course,
                             including hidden inputs for semester (SPRING) and year (2025) -->
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                            <input type="hidden" name="semester" value="SPRING">
                            <input type="hidden" name="year" value="2025">
                            <button type="submit" name="add_course">Add</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No courses found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>