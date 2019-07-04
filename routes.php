<?php

  $router->get('/', function() {
    print 'moin';
  });

  $router->group('/grp', function ($router) {
    $router->get('/bla', function() {
      print 'group';
    });

    $router->get('/u-{abc:\d+}', function(&$request, &$params) {
      var_dump($params);
    });
  });