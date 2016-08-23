<?php

$loader = require '../vendor/autoload.php';
$loader->add('Sample\\', __DIR__.'/../src/');

$dotenv = new Dotenv\Dotenv(__DIR__."/../");
$dotenv->load();


$app = new \Slim\App(require '../config/config.php');

$container = $app->getContainer();

$container['db'] = function ($c) {
    $pdo = new PDO("mysql:host=" . getenv('MYSQL_HOST') . ";dbname=" . getenv('MYSQL_DB_NAME'),
        getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


$app->get('/today/todos', 'Sample\\Controller\\TodoController:today');


$app->get('/todo/', 'Sample\\Controller\\TodoController:index');
$app->get('/todo/{id}', 'Sample\\Controller\\TodoController:read');
$app->post('/todo/', 'Sample\\Controller\\TodoController:create');
$app->put('/todo/{id}', 'Sample\\Controller\\TodoController:update');
$app->delete('/todo/{id}', 'Sample\\Controller\\TodoController:delete');


$app->get('/todo/{todo_id}/reminder/', 'Sample\\Controller\\ReminderController:index');
$app->get('/todo/{todo_id}/reminder/{id}', 'Sample\\Controller\\ReminderController:read');
$app->post('/todo/{todo_id}/reminder/', 'Sample\\Controller\\ReminderController:create');
$app->put('/todo/{todo_id}/reminder/{id}', 'Sample\\Controller\\ReminderController:update');
$app->delete('/todo/{todo_id}/reminder/{id}', 'Sample\\Controller\\ReminderController:delete');


$app->run();