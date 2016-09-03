# PHP-Router

Router for PHP. Simple, lightweight and convenient.

## Requirements

 * PHP 5.3.0+

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

      ```
      $router = new \Delight\Router\Router();
      ```

    * for any subdirectory

      ```
      $router = new \Delight\Router\Router('/my/base/path');
      ```

 1. Add some routes and map them to callback functions

    * static route

      ```
      $router->get('/', function () {
          // do something
      });
      ```

    * route with dynamic parameters

      ```
      $router->get('/users/:id/photo', function ($id) {
          // get the photo for user `$id`
      });
      ```

      The values of parameters matched in the URL can be captured as arguments in the callback.

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
