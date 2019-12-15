<?php

error_reporting(E_ERROR | E_PARSE);

require_once __DIR__.'/./shared/Headers.php';
require_once __DIR__.'/./classes/Request.php';
require_once __DIR__.'/./classes/Router.php';
require_once __DIR__.'/./classes/Auth.php';
require_once __DIR__.'/./classes/User.php';
require_once __DIR__.'/./classes/Event.php';
require_once __DIR__.'/./classes/Comment.php';
require_once __DIR__.'/./classes/Tool.php';

$router = new Router(new Request);

# GET

// HOME
$router->get('/', function() {
    return <<<HTML
        <!doctype html>
        <html>
            <head>
                <title>SafeSoft - REST API</title>
            </head>
            <body>
                <h1>REST API - SafeSoft</h1>
                <h3>Usage:</h3>
                <p>GET -> http://{$_SERVER['HTTP_HOST']}/user/{code} : being <code>{code}</code> the user code id (remove type prefix)</p>
                <br>
                <hr>
                <br>
                <p>POST -> http://{$_SERVER['HTTP_HOST']}/user/create : being the post params a json/form-data structure as a body content...</p>
        <pre>
            {
                "rut" : String,
                "firstname" : String,
                "lastname" : String,
                "phone" : Integer,
                "street" : String,
                "house_number" : String,
                "city" : String,
                "state" : String,
                "country" : String,
                "type" : Integer,
                "email" : String,
                "pass" : String,
                "parent_org" : String
            }
        </pre>
                <hr>
            </body>
        </html>
    HTML;
});

// USER
$router->get('/user/{code}', function ($request) {
    $user = new User();

    return json_encode($user->get((String)$request->params->code, " ", JSON_UNESCAPED_UNICODE));
});

$router->get('/user/rut/{rut}', function ($request) {
    $user = new User();

    return json_encode($user->getByRut((String)$request->params->rut));
});

$router->get('/user/delete/{code}', function ($request) {
    $user = new User();

    return json_encode($user->delete((String)$request->params->code));
});

// TOKEN
$router->get('/token/{token}', function ($request) {
    $user = new User();

    return json_encode($user->get(" ", (String)$request->params->token));
});

// EVENT
$router->get('/event/{id}', function ($request) {
    $event = new Event();

    return json_encode($event->get((String)$request->params->id));
});

$router->get('/event/delete/{id}', function ($request) {
    $event = new Event();

    return json_encode($event->delete((String)$request->params->id));
});

// COMMENT
$router->get('/comment/{id}', function ($request) {
    $comment = new Comment();

    return json_encode($comment->get((String)$request->params->id));
});

$router->get('/comment/delete/{id}', function ($request) {
    $comment = new Comment();

    return json_encode($comment->delete((String)$request->params->id));
});

// TOOL
$router->get('/tool/{id}', function($request) {
    $tool = new Tool();

    return json_encode($tool->get((String)$request->params->id));
});

$router->get('/tool/delete/{id}', function ($request) {
    $tool = new Tool();

    return json_encode($tool->delete((String)$request->params->id));
});

# POST requests
$router->post('/auth', function($request) {
    $params = $request->getBody();

    $auth = new Auth($params['id'], $params['key'], $params['criteria']);

    return json_encode($auth->authenticate());
});


// USERS
$router->post('/user/create', function($request) {
    $user = new User();

    return json_encode($user->create($request->getBody()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

// EVENTS
$router->post('/event/create', function($request) {
    $event = new Event();

    $outer_data = $event->create($request->getBody());
    
    return json_encode($outer_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

// COMMENTS
$router->post('/comment/create', function($request) {
    $comment = new Comment();
    
    return json_encode($comment->create($request->getBody()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

// TOOLS
$router->post('/tool/create', function($request) {
    $tool = new Tool();
    
    return json_encode($tool->create($request->getBody()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

?>
