<?php
  namespace App;

  use Pages\{Request, Response};

  class ViewController {
    static function index(Request &$request, Response &$response) {
      return $response->view(['index'], ['request' => $request]);
    }
  }