<?php
require_once 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
$prettyPrinter = new PrettyPrinter\Standard;
$content = file_get_contents('./dummy.php');


try {
    $stmts = $parser->parse($content);

    foreach ($stmts as $stmt) {
        if (get_class($stmt) == 'PhpParser\Node\Stmt\Class_') {
            foreach ($stmt->stmts as $classInnerStmt) {
                $attributes = $classInnerStmt->getAttributes();
                if (!isset($attributes['comments'])) {
                    var_dump($classInnerStmt);
                }
            }
        }
    }
} catch (Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}

