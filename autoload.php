<?php
  spl_autoload_register(function(string $path) {
    $parts = explode('\\', $path);
    $root = array_shift($parts);
    require_once __DIR__.DIRECTORY_SEPARATOR.strtolower($root).DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $parts).'.php';
  });