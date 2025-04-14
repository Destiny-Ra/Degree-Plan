<?php
    // Start the session, load environment variables, and define Auth0 constants.
    
    session_start();

    
    require 'vendor/autoload.php';
    (Dotenv\Dotenv::createImmutable(__DIR__))->load();

    define('ROUTE_URL_INDEX', rtrim($_ENV['AUTH0_BASE_URL'], '/'));
    define('ROUTE_URL_LOGIN', ROUTE_URL_INDEX . '/login');
    define('ROUTE_URL_CALLBACK', ROUTE_URL_INDEX . '/home_page/home_page.php');
    define('ROUTE_URL_LOGOUT', ROUTE_URL_INDEX . '/logout');

    // Now include header.php so that it uses the defined constants.
    require "header.php";

    // Set up the Auth0 SDK
    $auth0 = new \Auth0\SDK\Auth0([
        'domain' => $_ENV['AUTH0_DOMAIN'],
        'clientId' => $_ENV['AUTH0_CLIENT_ID'],
        'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'],
        'cookieSecret' => $_ENV['AUTH0_COOKIE_SECRET']
    ]);

    use Steampixel\Route;

    // Define Auth0-related routes
    Route::add('/', function() use ($auth0) {
        $session = $auth0->getCredentials();
        if ($session === null) {
            echo '<p>Please log in using the header link.</p>';
            return;
        }
        
        echo '<p>You can now <a class="nav-link" href="' . ROUTE_URL_LOGOUT . '">log out</a>.</p>';
    });

    Route::add('/login', function() use ($auth0) {
        $auth0->clear();
        header("Location: " . $auth0->login(ROUTE_URL_CALLBACK));
        exit;
    });

    Route::add('/callback', function() use ($auth0) {
        $auth0->exchange(ROUTE_URL_CALLBACK);
        $credentials = $auth0->getCredentials();
        if ($credentials !== null) {
            $_SESSION['user'] = $credentials->user;
        }
        header("Location: " . ROUTE_URL_INDEX) . "/home_page/home_page.php";
        exit;
    });

    Route::add('/logout', function() use ($auth0) {
        header("Location: " . $auth0->logout(ROUTE_URL_INDEX));
        exit;
    });

    Route::run('/');

// the code below is potentially removable
?>
<main>
    <div class="wrapper-main">
        <section class="section-default">
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<p class="login-status">You are logged in!</p>';
                } else {
                    echo '<p class="login-status">You are logged out!</p>';
                }
            ?>
        </section>
    </div>
</main>
</body>
</html>
