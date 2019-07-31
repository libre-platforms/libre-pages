<?php
  namespace App;

  use Framework\{Request, Response};

  class ViewController {
    static function index(Request &$request, Response &$response) {
      return $response->view(['index'], ['request' => $request]);
    }
  }