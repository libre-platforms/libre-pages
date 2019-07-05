<?php
  namespace Framework;

  function makeAssetLoader(Request& $request) {
    $asset_base = "http".($request->https ? 's' : '').'://'.$request->server_name.':'.$request->server_port.'/assets/';
    return function(string $stored_asset_path) use (&$asset_base) {
      return $asset_base.$stored_asset_path;
    };
  }