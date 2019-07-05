<?php
  use Framework\Router;

  $router->get('/', function() {
    print 'moin';
  });

  $router->group('/grp', function ($router) {
    $router->get('/bla', function() {
      print 'group';
    });

    $router->get('/u-{abc:\d+}', Router::make_handler_chain([
      function(&$request, &$response, $next) {
        $response->status(404);
        return $next($request, $response);
      },
      function(&$request, &$response) {
        $loader = Framework\makeAssetLoader($request);
        $response->json(['hello_image' => $loader('img/hello-world.png')]);
        return $response;
      }
    ]));
  });