<?php
  declare(strict_types=1);
  namespace Framework;

  /**
   * Returns a function, which returns absolute links to files in the 'assets' folder.
   */
  function make_asset_loader(Request& $request) {
    $asset_base = "http".($request->https ? 's' : '').'://'.$request->server_name.':'.$request->server_port.'/assets/';
    return function(string $stored_asset_path) use (&$asset_base) {
      return $asset_base.$stored_asset_path;
    };
  }

  /**
   * Returns a function, which may load and evaluate views, located in the given views folder.
   */
  function make_view_evaluator(string $view_base_path) {
    return function(array $view, array $data = []) use (&$view_base_path) {
      extract($data);
      require $view_base_path.implode(DIRECTORY_SEPARATOR, $view).'.php';
    };
  }