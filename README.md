# PHP-Router

Router for PHP. Simple, lightweight and convenient.

## Requirements

 * PHP 5.3.0+

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

 2. Include the library

    ```
    require_once(__DIR__.'/libs/PhpRouter/Router.php');
    ```

 3. Create a new `Router` instance

    * for the web root

      ```
      $router = new \Delight\Router\Router();
      ```

    * for any subdirectory

      ```
      $router = new \Delight\Router\Router('/my/base/path');
      ```

 4. Add some routes and map them to callback functions

    * static route

      ```
      $router->get('/', function () {
          // do something
      }
      ```

    * route with dynamic parameters

      ```
      $router->get('/users/:id/photo', function ($id) {
          // get the photo for user `$id`
      }
      ```

      The values of parameters matched in the URL can be captured as arguments in the callback.

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

```
Copyright (c) delight.im <info@delight.im>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
