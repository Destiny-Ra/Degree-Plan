
<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="nav-header-main">
            <ul>
                <li><a class="nav-link" href="<?php echo defined('ROUTE_URL_INDEX') ? ROUTE_URL_INDEX : 'index.php'; ?>">Home</a></li>
                <li><a class="nav-link" href="<?php echo defined('ROUTE_URL_INDEX') ? ROUTE_URL_INDEX . '/portfolio' : '#'; ?>">Portfolio</a></li>
                <li><a class="nav-link" href="<?php echo defined('ROUTE_URL_INDEX') ? ROUTE_URL_INDEX . '/about' : '#'; ?>">About Me</a></li>
                <li><a class="nav-link" href="<?php echo defined('ROUTE_URL_INDEX') ? ROUTE_URL_INDEX . '/contact' : '#'; ?>">Contact</a></li>
                <?php
                if (isset($_SESSION['userId'])) {
                    echo '<li><a class="nav-link" href="' . (defined('ROUTE_URL_LOGOUT') ? ROUTE_URL_LOGOUT : '#') . '">Logout</a></li>';
                } else {
                    echo '<li><a class="nav-link" href="' . (defined('ROUTE_URL_LOGIN') ? ROUTE_URL_LOGIN : '#') . '">Login</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
