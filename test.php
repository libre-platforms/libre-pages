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
  require_once __DIR__.DIRECTORY_SEPARATOR.'autoload.php';
  require_once __DIR__.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'functions.php';

  $files = scandir(__DIR__.DIRECTORY_SEPARATOR.'tests');
  $test_suites_classes = [];

  foreach ($files as $file) {
    if (Framework\ends_with($file, '.php')) {
      $test_suites_classes[] = 'Tests\\'.substr($file, 0, strlen($file) - 4);
    }
  }

  $test_results = array_reduce($test_suites_classes, function($accu, $test_class){ $accu[$test_class] = (new $test_class)->run(); return $accu; }, []);

  $test_count = 0;
  $test_failed_count = 0;
  $assertion_count = 0;
  $assertion_failed_count = 0;

  foreach ($test_results as $suite => $tests) {
    print 'Results fo suite '.$suite.':'.PHP_EOL;
    foreach ($tests as $test_name => $assertions) {
      print '  '.$test_name.PHP_EOL;
      ++$test_count;
      $test_failed = false;
      $assertion_count += count($assertions);
      foreach ($assertions as [$success, $failed_message, $trace]) {
        if (!$success) {
          $test_failed = true;
        }
      }

      if ($test_failed) {
        ++$test_failed_count;
      }
    }
  }

  $test_succees_count = $test_count - $test_failed_count;
  $test_success_rate = round($test_succees_count / $test_count * 100, 2);

  print "Tests run: {$test_count}; tests succeeded: {$test_succees_count}; success rate: {$test_success_rate}%".PHP_EOL;