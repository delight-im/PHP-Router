# PHP-Router

Minimal router for PHP (fast + single file)

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
    require_once(__DIR__.'/libs/PhpRouter/PhpRouter.php');
    ```

 3. Create a new `PhpRouter` instance
    * for the web root

      ```
      $router = new PhpRouter();
      ```
    * for any subdirectory

      ```
      $router = new PhpRouter('/my/base/path');
      ```

 4. Add some routes and map them to callback functions
    * simple route

      ```
      $router->get('/', function () {
          // do something
      }
      ```

    * route with parameters

      ```
      $router->get('/users/:id/photo', function ($id) {
          // get the photo for user `$id`
      }
      ```

 5. Execute the router

    ```
    $router->run();
    ```

## Dependencies

 * PHP 5.3+

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

```
Copyright 2015 delight.im <info@delight.im>

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
