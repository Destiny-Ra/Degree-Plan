<?php
session_start();





// get env variables from .env file
require '../vendor/autoload.php';
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

// Optional: Connect to your database to fetch user-specific courses.
// (Adjust your DSN, username, and password as needed.)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_db", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming your courses table uses a column like user_id to link courses with the user.
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE user_id = :userId");
    $stmt->execute(['userId' => $user->sub]); // use the unique Auth0 user ID (sub)
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If the query fails, set courses to an empty array.
    $courses = [];
    // You may want to log $e->getMessage() in a real-world application.
}

// HTML SECTION BELOW
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UTPB Degree Plan Dashboard</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div class="page-container">
      <header class="main-header">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/TEMP/881cac5505b123328a6a56cfe1c06eed9ddbf3cf"
          alt="UTPB ICON"
          class="header-logo"
        />
      </header>

      <nav class="navigation-bar" role="navigation">
        <h1 class="page-title">Degree Plan</h1>
        <!-- Update the logout button to link to your Auth0 logout route -->
        <button class="logout-button" onclick="location.href='<?php echo defined('ROUTE_URL_LOGOUT') ? ROUTE_URL_LOGOUT : '#'; ?>'" aria-label="Logout from the system">
          Logout
        </button>
      </nav>

      <main class="main-content">
        <!-- Dynamically display the user's name -->
        <h2 class="student-greeting">Hi <?php echo htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8'); ?>,</h2>

        <section class="degree-section">
          <button class="section-title general-education" onclick="location.href='../general_education/general_education.php'">
            General Education
          </button>

          <button class="section-title" onclick="location.href='../bs_math/bs_math.php'">
            BS in Computer Science
            <span class="section-subtitle">Math Support Courses</span>
          </button>

          <button class="section-title" onclick="location.href='../track_page/track_page.php'">
            Computer Science Track
          </button>

          <button class="section-title" onclick="location.href='../math_minor/math_minor.php'">
            Math Minor
          </button>
        </section>
          
        </section>
      </main>

      <footer class="main-footer">
        <p>Copyright © 2024 The University of Texas Permian Basin</p>
      </footer>
    </div>
  </body>
</html>
