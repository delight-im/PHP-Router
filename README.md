# PHP-Router

Router for PHP. Simple, lightweight and convenient.

## Requirements

 * PHP 5.6.0+

## Installation

 * Install via [Composer](https://getcomposer.org/) (recommended)

   `$ composer require delight-im/router`

   Include the Composer autoloader:

   `require __DIR__.'/vendor/autoload.php';`

 * or

 * Install manually

   * Copy the contents of the [`src`](src) directory to a subfolder of your project
   * Include the files in your code via `require` or `require_once`

## Usage

 1. Enable URL rewriting on your web server

    * Apache (in `.htaccess` or `httpd.conf`)

      ```
      RewriteEngine On
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule . index.php [L]
      ```

    * Nginx (in `nginx.conf`)

      ```
      try_files $uri /index.php;
      ```

 1. Create a new `Router` instance

    * for the web root

      ```php
      $router = new \Delight\Router\Router();
      ```

    * for any subdirectory

      ```php
      $router = new \Delight\Router\Router('/my/base/path');
      ```

 1. Add some routes and map them to anonymous functions or closures

    * static route

      ```php
      $router->get('/', function () {
          // do something
      });
      ```

    * route with dynamic parameters

      ```php
      $router->get('/users/:id/photo', function ($id) {
          // get the photo for user `$id`
      });
      ```

      The values of parameters matched in the URL can be captured as arguments in the callback.

 1. Map routes to controller methods instead for more complex callbacks

    ```php
    // use static methods
    $router->get('/photos/:id/convert/:mode', 'PhotoController::myStaticMethod');
    // or
    // $router->get('/photos/:id/convert/:mode', [ 'PhotoController', 'myStaticMethod' ]);

    // or

    // instance methods
    $router->get('/photos/:id/convert/:mode', [ $myPhotoController, 'myInstanceMethod' ]);
    ```

 1. Inject arguments for access to further values and objects (prepended to those matched in the route)

    ```php
    class MyController {

        public static function someStaticMethod($database, $uuid) {
            // do something
        }

    }
    ```

    and

    ```php
    $database = new MyDatabase();

    // ...

    $router->delete('/messages/:uuid', 'MyController::someStaticMethod', [ $database ]);
    ```

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
