<?php

require_once './shared/Headers.php';
require_once './classes/Request.php';
require_once './classes/Router.php';
// require_once 'classes/Auth.php';

$router = new Router(new Request);

# GET requests
$router->get('/', function() {
    return <<<HTML
        <!doctype html>
        <html>
            <head>
                <title>SafeSoft - REST API</title>
            </head>
            <body>
                <h1>REST API - SafeSoft</h1>
            </body>
        </html>
    HTML;
});

$router->get('/user', function($request) {
    return <<<HTML
        <h1>User request with GET method</h1>
    HTML;
});

# POST requests
$router->post('/user/insert', function($request) {
    return json_encode($request->getBody());
});

$router->post('/user/update', function($request) {
    return json_encode($request->getBody());
});

?>
