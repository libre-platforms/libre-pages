<?php
  namespace App;

  use Pages\{Request, Response};

  /**
   * Controller for simple views.
   */
  class ViewController {
    static function index(Request &$request, Response &$response) {
      return $response->view(['index'], ['request' => $request]);
    }
  }