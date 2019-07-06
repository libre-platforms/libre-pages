<?php
//  Copyright (C) 2019 Jörn Neumeyer
//
//  This file is part of LibrePages.
//
//  LibrePages is free software: you can redistribute it and/or modify
//  it under the terms of the GNU Affero General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  LibrePages is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU Affero General Public License for more details.
//
//  You should have received a copy of the GNU Affero General Public License
//  along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
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