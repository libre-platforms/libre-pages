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
  namespace Pages;

  /**
   * Returns a function, which returns absolute links to files in the 'assets' folder.
   * 
   * @param &\Pages\Request $request The request
   */
  function make_asset_loader(Request& $request) {
    $asset_base = "http".($request->https ? 's' : '').'://'.$request->server_name.':'.$request->server_port.'/assets/';
    return fn(string $stored_asset_path) => $asset_base.$stored_asset_path;
  }

  /**
   * Returns a function, which may load and evaluate views, located in the given views folder.
   */
  function make_view_evaluator(string $view_base_path) {
    return fn(array $view, array $data = []) =>
      extract($data) && require $view_base_path.implode(DIRECTORY_SEPARATOR, $view).'.php';
  }

  /**
   * Checks if the haystack ends with the provided needle.
   * 
   * @param string $haystack The string which end will be checked.
   * @param string $needle The needle to look for at the end of the haystack.
   * 
   * @return bool The matching success.
   */
  function ends_with(string $haystack, string $needle) {
    $haystack_length = strlen($haystack);
    $needle_length = strlen($needle);
    return strrpos($haystack, $needle) === $haystack_length - $needle_length;
  }

  /**
   * This function generates HTML code, to populate a `code` element with a 7 row
   * deep snippet of the code's content, given the provided line number.
   * 
   * @param string $text The text which one would like to peek at a certain line.
   * @param int $line The line that should be peeked.
   * 
   * @return string The text view with highlighting for the specified line.
   */
  function generate_file_view(string $text, int $line) {
    $code_lines = explode("\n", $text);
    $line_count = count($code_lines);
    $slice_length = 7;
    $first_line_index = $line - 4;
    if ($first_line_index < 0) {
      $slice_length += $first_line_index;
      $first_line_index = 0;
    }
    $snippet = array_slice($code_lines, $first_line_index, $slice_length);
    $snippet_count = count($snippet);
    $highlight_index = $line >= $snippet_count ? 3 : $line - 1;
    for ($i = 0; $i < $snippet_count; ++$i) {
      $snippet[$i] = (str_pad((string)($first_line_index + $i + 1), 6, ' ') . ' | ' . $snippet[$i]);
      if ($i === $highlight_index) $snippet[$i] = "<strong>{$snippet[$i]}</strong>";
    }
    $snippet = implode("\r\n", $snippet);
    return $snippet;
  }