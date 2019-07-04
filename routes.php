<?php

  $router->get('/', function() {
    print 'moin';
  });

  $router->group('/grp', function ($router) {
    $router->get('/bla', function() {
      print 'group';
    });

    $router->get('/u-{abc:\d+}', function(&$request, &$response) {
      var_dump($request->params());
    });
  });