#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

use Colors\Color;

$c = new Color();
$options = getopt('f:');


if (count($options) && isset($options['f'])) {
    $file_name = $options['f'];
    echo $c("Convert {$file_name}")->green->bold .PHP_EOL;
    $comment = new dummy\Comment($file_name);
    echo $comment->generateNewCode();
} else {
    echo $c("Please run with -f filename")->red->bold . PHP_EOL;
}
