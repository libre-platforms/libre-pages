<?php
/**
 * This file is part of LibrePages.
 *
 * LibrePages is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * LibrePages is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
 * 
 * @author    Jörn Neumeyer <contact@joern-neumeyer.de>
 * @copyright 2019 Jörn Neumeyer
*/

  declare(strict_types=1);

  require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
  $view_evaulator = Pages\make_view_evaluator(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR);

  define('APP_ROOT', __DIR__);
  define('APP_START', microtime(true));

  ob_start();

  $router = new Pages\Router();

  $request_path = $_SERVER['PATH_INFO'] ?? '/';
  
  if ($request_path !== '/') {
    $request_path = rtrim($request_path, '/');
  }

  try {
    require __DIR__.DIRECTORY_SEPARATOR.'routes.php';
    $handler_with_params = $router->get_handler($_SERVER['REQUEST_METHOD'], $request_path);

    if (is_array($handler_with_params)) {
      [$handler, $params] = $handler_with_params;
      if (is_array($handler)) {
        $handler = Pages\Router::make_handler_chain($handler);
      }
      $request = Pages\Request::from_current_request($params);
      $response = new Pages\Response;
      $response->set_view_evaluator($view_evaulator);
      $response = $handler($request, $response);
      print $response->send();
    } else {
      print '[NO HANDLER FOUND]';
    }
  } catch (\Throwable $ex) {
    $ob = ob_get_contents();
    ob_clean();
    http_response_code(500);
    $asset_loader = Pages\make_asset_loader($request);
      ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Error!</title>
    <style>
    @font-face {
      font-family: Mechanical;
      src: url("<?=$asset_loader('fonts/Mechanical.otf')?>") format("opentype");
    }
    </style>
    <link type="text/css" rel="stylesheet" href="<?=$asset_loader('css/application-error.css')?>" />
  </head>
  <body>
    <h1>An uncaught Exception has been thrown!</h1>
    Message: <?=$ex->getMessage()?><br />
    File with line: <?=$ex->getFile().':'.$ex->getLine()?><br />

    <h3>Stack Trace</h3>
    <?=$ex->getFile()?>
          <pre><code>
<?=Pages\generate_file_view(file_get_contents($ex->getFile()), $ex->getLine())?></code></pre>
        <?php foreach ($ex->getTrace() as $t): ?>
          <?php if(isset($t['file'])): ?>
<?=$t['file']?>
          <pre><code>
<?=Pages\generate_file_view(file_get_contents($t['file']), $t['line'])?></code></pre>
          <!--<tr>
            <td></td>
            <td><?=$t['line']?></td>
            <td><?=$t['function']?></td>
          </tr>-->
          <?php endif; ?>
        <?php endforeach; ?>
    <?php if ($ob): ?>
    <p>Output generated before exception:</p>
    <pre><?=$ob?></pre>
    <?php endif; ?>
  </body>
</html>
<?php
  }

  $response_text = ob_get_contents();
  ob_end_clean();

  print $response_text;