<?php
  namespace App;

  use Framework\{Request, Response};

  class TestController {
    static function user_by_id(Request &$request, Response &$response) {
      $response->write('hello from controller');
      return $response;
    }
  }