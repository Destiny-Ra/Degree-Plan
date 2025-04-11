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










?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>General Education - UTPB Degree Plan</title>
    <link rel="stylesheet" href="style.css" />
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
          <h3 class="section-title">General Education</h3>

          <div class="course-table">
            <div class="table-header">
              <div class="header-cell status-cell"></div>
              <div class="header-cell">Course Number</div>
              <div class="header-cell">Course</div>
              <div class="header-cell">Credits</div>
            </div>

            <div class="table-row">
              <div class="cell status-cell">-</div>
              <div class="cell"></div>
              <div class="cell"></div>
              <div class="cell">3</div>
            </div>

            <div class="table-row">
              <div class="cell status-cell">Taken</div>
              <div class="cell"></div>
              <div class="cell"></div>
              <div class="cell"></div>
            </div>
          </div>

          <div class="update-section">
            <button class="update-button">Update</button>
          </div>

          <div class="credit-summary">
            <p class="summary-text">
              Credit Needed : ----<br />
              Total : ----
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