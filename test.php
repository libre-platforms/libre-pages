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

  $use_color = in_array('--color', $argv);

  /**
   * A helper for console print colors.
   */
  final class PrintColor {
    const red = "\033[0;31m";
    const green = "\033[0;32m";
    const yellow = "\033[1;33m";
    const no_color = "\033[0m";
  }

  function report_print(string $text, $color) {
    global $use_color;
    if ($use_color) {
      print $color.$text.PrintColor::no_color;
    } else {
      print $text;
    }
  }

  require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

  $files = scandir(__DIR__.DIRECTORY_SEPARATOR.'tests');
  $test_suites_classes = [];

  foreach ($files as $file) {
    if (Pages\ends_with($file, '.php')) {
      $test_suites_classes[] = 'Tests\\'.substr($file, 0, strlen($file) - 4);
    }
  }

  $time_before_tests = microtime(1);

  $test_results = array_reduce($test_suites_classes, function($accu, $test_class){ $accu[$test_class] = (new $test_class)->run(); return $accu; }, []);

  $time_after_tests = microtime(1);
  $time_passed_during_testing = $time_after_tests - $time_before_tests;
  $ms_passed_during_testing = floor($time_passed_during_testing * 1000);

  $test_count = 0;
  $test_failed_count = 0;
  $assertion_count = 0;
  $assertion_failed_count = 0;

  foreach ($test_results as $suite => $tests) {
    print 'Results for suite '.$suite.':'.PHP_EOL;
    if ($tests === []) {
      report_print('  Test suite is empty!'.PHP_EOL, PrintColor::yellow);
    } else {
      foreach ($tests as $test_name => $assertions) {
        ++$test_count;
        $test_failed = false;
        $assertion_count += count($assertions);
        $fail_messages = [];
        foreach ($assertions as [$success, $failed_message]) {
          if ($success) {
            continue;
          }
          ++$assertion_failed_count;
          $test_failed = true;
          $fail_messages[] = "    {$failed_message}".PHP_EOL;
        }
  
        print '  '.$test_name;
  
        if (!$test_failed) {
          report_print(' passed'.PHP_EOL, PrintColor::green);
        } else {
          report_print(' fails'.PHP_EOL, PrintColor::red);
          ++$test_failed_count;
          foreach ($fail_messages as $fail) {
            print $fail;
          }
          print PHP_EOL;
        }
      }
    }

    print PHP_EOL;
  }

  print PHP_EOL.PHP_EOL;

  $test_success_count = $test_count - $test_failed_count;
  $test_success_rate = round($test_success_count / $test_count * 10000) / 100;

  $assertion_success_count = $assertion_count - $assertion_failed_count;
  $assertion_success_rate = round($assertion_success_count / $assertion_count * 10000) / 100;

  if ($test_failed_count > 0) {
    report_print('TESTS FAILED!'.PHP_EOL, PrintColor::red);
  } else {
    report_print('TESTS SUCCEEDED!'.PHP_EOL, PrintColor::green);
  }

  print "Tests run: {$test_count}; tests succeeded: {$test_success_count}; success rate: {$test_success_rate}%".PHP_EOL;
  print "Assertions done: {$assertion_count}; assertions succeeded: {$assertion_success_count}; success rate: {$assertion_success_rate}%".PHP_EOL;
  if ($ms_passed_during_testing < 100) {
    print $ms_passed_during_testing.' m';
  } else {
    print ($ms_passed_during_testing * 1000).'passed';
  }
  print 's passed'.PHP_EOL;

  if ($test_failed_count > 0) {
    exit(1);
  }