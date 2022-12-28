<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\PostController;
use \app\controllers\SearchController;
use app\core\Application;

$app = new Application(dirname(__DIR__));

//Home routes
$app->router->get('/', [SiteController::class, 'home']);
$app->router->post('/', [SiteController::class, 'favorite']);

//Fav
$app->router->get('/fav', [SiteController::class, 'fav']);
$app->router->post('/fav', [SiteController::class, 'favDelete']);

//AJAX search
$app->router->get('/search', [SearchController::class, 'index']);
$app->router->post('/search/show', [SearchController::class, 'showPosts']);

//Upload routes
$app->router->get('/upload', [PostController::class, 'uploadFile']);
$app->router->post('/upload', [PostController::class, 'uploadFile']);

//Auth routes
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);

$app->router->post('/login', [AuthController::class, 'login']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/logout', [AuthController::class, 'logout']);

//Pagination
$app->router->post('/next', [PostController::class, 'paginateNext']);
$app->router->post('/prev', [PostController::class, 'paginatePrev']);

$app->run();