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
        $response->status_code(404);
        return $next($request, $response);
      },
      function(&$request, &$response) {
        return $response;
      }
    ]));
  });