# Simple php router for API

This is a simple php router to create simplte API written in PHP.

The routes are handled by regex, where parameters are extracted.

The handling callback in addRoute will receive an array : 
```$xslt
[
    'URL_PARAMS' : [/*list of parameters received fro the regex]
    'BODY_DATA' : associative array of json received in the body of the request
]
```
## Use this project

### Clone the repo

```bash
git clone 
```

### Add controller.php and index.php
Create `index.php` as:

```php
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
```

then in your controller, write the functions handling the routes as:

```php
<?php
class Controller {

    function __construct() {
    }
    
    /**
     * List all articles
     * @return string
     *      Json of all articles
     */
    function listArticles(): string {
        $articles = Article::queryArticles();
        return json_encode($articles);
    }
}
``` 
