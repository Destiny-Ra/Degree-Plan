<?php
session_start();
session_destroy();

require_once 'auth_config.php';

$logout_url = sprintf(
    'https://%s/v2/logout?client_id=%s&returnTo=%s',
    'dev-muvbzhy1gdnjum2r.us.auth0.com',
    'pDChAGpnmKNqBhd5ZQqekijFBJXVyOzU',
    urlencode('http:127.0.0.1:3000/index.php')
);