#!/bin/sh
clear
php test.php $*
while inotifywait -e modify tests/*.php app/*.php framework/*.php test.php
do
  clear
  clear
  php test.php $*
done