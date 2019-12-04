<?php

require_once './shared/Headers.php';
require_once './classes/Request.php';
require_once './classes/Router.php';
require_once './classes/Auth.php';

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

$router->get('/user', function() {
    return <<<HTML
        <h1>User request with GET method</h1>
    HTML;
});

$router->get('/value', function() {
    $out = array();

    foreach ($_SERVER as $key => $value) {
        $out[$key] = $value;
    }

    return json_encode($out);

    // return json_encode($_REQUEST, JSON_UNESCAPED_SLASHES);
});

# POST requests
$router->post('/auth', function($request) {
    $params = $request->getBody();

    $auth = new Auth($params['id'], $params['key'], $params['criteria']);

    return json_encode($auth->authenticate());
});

$router->post('/user/insert', function($request) {
    return json_encode($request->getBody());
});

$router->post('/user/update', function($request) {
    return json_encode($request->getBody());
});

?>
