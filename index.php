<?php
/**
 * Created by PhpStorm.
 * User: maxime
 * Date: 17/11/17
 * Time: 18:32
 */
require('Router.php');
require('Controller.php');
require('RouterUtils.php');
$router = Router::getInstance();
$controller = new Controller();

/**
 * Create all the routes
 */

$router->addRoute('~^/articles/?$~', Router::GET,
    function ($args) use ($controller) {
            RouterUtils::response($controller->listArticles());
    })
    ->addRoute('~^/articles/?$~', Router::POST,
        function ($args) use ($controller) {
            RouterUtils::response($controller->insertNewArticle($args));
        })
    ->addRoute('~^/articles/(\d+)/?$~', Router::GET,
        function ($args) use ($controller) {
            RouterUtils::response($controller->getArticle($args));
        })

    ->addRoute('~^/articles/(\d+)/?$~', Router::DELETE,
        function ($args) use ($controller) {
            RouterUtils::response($controller->deleteArticleById($args));
        })

    ->addRoute('~^/articles/(\d+)/?$~', Router::PATCH,
        function ($args) use ($controller) {
            RouterUtils::response($controller->updateArticleById($args));
        });

$data = RouterUtils::getBodyData();

/**
 * Get the function corresponding to the request
 */
$url = RouterUtils::extractRealApiRoute($_SERVER['REQUEST_URI']);
$result = $router->match($url, $_SERVER['REQUEST_METHOD']);

/**
 * If no route found, show 404
 */
if (RouterUtils::isRouteFound($result)) {
    RouterUtils::executeRoute($result, $data);
} else {
    RouterUtils::response(cError::_404("No route found"));
}