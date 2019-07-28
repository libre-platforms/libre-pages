#!/bin/sh
clear
php test.php $*
while inotifywait -e modify tests/*.php app/*.php framework/*.php
do
  clear
  php test.php $*
done