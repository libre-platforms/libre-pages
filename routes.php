<?php
  use Framework\Router;

  $router->get('/', function(&$request, &$response) {
    return $response->write('moin');
  });

  $router->group('/grp', function ($router) {
    $router->get('/bla', function(&$request, &$response) {
      return $response->write('bla');
    });

    $router->get('/u-{abc:\d+}', Router::make_handler_chain([
      function(&$request, &$response, $next) {
        $response->status(404);
        return $next($request, $response);
      },
      function(&$request, &$response) {
        $loader = Framework\make_asset_loader($request);
        // $response->json(['hello_image' => $loader('img/hello-world.png')]);
        $response->view(['welcome']);
        $response->set_cookie('foo', 'bar');
        return $response;
      }
    ]));

    $router->post('/u-{abc:\d+}', function(&$request, &$response) {
      var_dump($request->files);
      return $response;
    });
  });