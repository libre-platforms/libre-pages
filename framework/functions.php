<?php
  namespace Framework;

  function make_asset_loader(Request& $request) {
    $asset_base = "http".($request->https ? 's' : '').'://'.$request->server_name.':'.$request->server_port.'/assets/';
    return function(string $stored_asset_path) use (&$asset_base) {
      return $asset_base.$stored_asset_path;
    };
  }

  function make_view_evaluator(string $view_base_path) {
    return function(array $view, array $data = []) use (&$view_base_path) {
      extract($data);
      require $view_base_path.implode(DIRECTORY_SEPARATOR, $view).'.php';
    };
  }