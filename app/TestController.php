<?php
  namespace App;

  class TestController {
    static function user_by_id(&$request, &$response) {
      $response->write('hello from controller');
      return $response;
    }
  }