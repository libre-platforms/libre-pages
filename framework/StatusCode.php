<?php
  namespace Framework;

  class StatusCode {
    const HTTP_OK = 200;

    private static $status_messages = [
      200 => 'OK',
    ];

    static function message_of_code(int $code) {
      return self::$status_messages[$code] ?? '';
    }
  }