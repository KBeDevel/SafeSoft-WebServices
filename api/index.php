<?php

require_once __DIR__.'/./shared/Headers.php';
require_once __DIR__.'/./classes/Request.php';
require_once __DIR__.'/./classes/Router.php';
require_once __DIR__.'/./classes/Auth.php';
require_once __DIR__.'/./classes/User.php';
require_once __DIR__.'/./classes/Event.php';

$router = new Router(new Request);

# GET requests

// $data['rut'], $data['firstname'], $data['lastname'], $data['phone'], $data['street'], $data['house_number'], $data['city'], $data['state'], $data['country'], $data['type'], $data['email'], $data['pass'], $generated_token, $data['parent_org']

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

    return json_encode($user->get((String)$request->params->code, " "));
});

$router->get('/user/rut/{rut}', function ($request) {
    $user = new User();

    return json_encode($user->getByRut((String)$request->params->rut));
});

// $router->get('/user/delete/{code}', function ($request) {
//     $user = new User();

//     return json_encode($user->getByRut((String)$request->params->code));
// });

// USER BY TOKEN
$router->get('/token/{token}', function ($request) {
    $user = new User();

    return json_encode($user->get(" ", (String)$request->params->token));
});

// EVENT
$router->get('/event/{id}', function ($request) {
    $user = new Event();

    return json_encode($user->get((String)$request->params->id));
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

    $outer_data = $user->create($request->getBody());
    
    return json_encode($outer_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

// $router->post('/user/update', function($request) {
//     return json_encode($request->getBody());
// });

// EVENTS
$router->post('/event/create', function($request) {
    $event = new Event();

    $outer_data = $event->create($request->getBody());
    
    return json_encode($outer_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

?>
