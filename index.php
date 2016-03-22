#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

use Colors\Color;

$c = new Color();
$options = getopt('f:w:');

if (count($options) && isset($options['f'])) {
    $file_path = $options['f'];
    $file_name = basename($file_path);
    echo $c("Convert {$file_name}")->green->bold .PHP_EOL;
    $comment = new dummy\Comment($file_path);
    if (array_search('-w', (array)$argv)) {
        $output_path = $file_path;
        if (isset($options['w'])) {
            $output_path = $options['w'];
        }
        $output_file_name = basename($output_path);
        echo $c("Output to {$output_file_name}")->green->bold .PHP_EOL;
        $return = file_put_contents($output_path, $comment->generateNewCode());
        if (!is_numeric($return)) {
            echo $c("Failed to output to {$output_path}")->red->bold . PHP_EOL;
        }
    } else {
        echo $comment->generateNewCode();
    }
} else {
    echo $c("Please run with -f filename [-w [output file path]]")->red->bold . PHP_EOL;
}
