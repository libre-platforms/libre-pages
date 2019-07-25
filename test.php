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
      ++$test_count;
      $test_failed = false;
      $assertion_count += count($assertions);
      $fail_messages = [];
      foreach ($assertions as [$success, $failed_message, $trace]) {
        if ($success) {
          continue;
        }
        $test_failed = true;
        $fail_messages[] = "    {$failed_message} Line {$trace['line']}".PHP_EOL;
      }

      print '  '.$test_name.' ';

      if (!$test_failed) {
        print 'passed'.PHP_EOL;
      } else {
        print 'fails'.PHP_EOL;
        ++$test_failed_count;
        foreach ($fail_messages as $fail) {
          print $fail;
        }
        print PHP_EOL;
      }
    }
  }

  $test_succees_count = $test_count - $test_failed_count;
  $test_success_rate = round($test_succees_count / $test_count * 10000) / 100;

  print "Tests run: {$test_count}; tests succeeded: {$test_succees_count}; success rate: {$test_success_rate}%".PHP_EOL;