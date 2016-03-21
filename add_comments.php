#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\ClassMethod;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
$prettyPrinter = new PrettyPrinter\Standard;
$content = file_get_contents('./dummy.php');


try {
    $stmts = $parser->parse($content);
    $withNamespace = false;
    $rootStmt = $stmts[0];
    if (get_class($rootStmt) == 'PhpParser\Node\Stmt\Namespace_') {
        $withNamespace = true;
        $clasStmts = $rootStmt->stmts;
    } else {
        $clasStmts = $stmts;
    }
    foreach ($clasStmts as $stmt) {
        if (get_class($stmt) == 'PhpParser\Node\Stmt\Class_') {
            $classAttributes = $stmt->getAttributes();
            if (!isset($attributes['comments'])) {
                $doc = new Doc(generate_class_comment($stmt));
                $stmt->setAttribute('comments', [$doc]);
            }
            foreach ($stmt->stmts as $classInnerStmt) {
                $attributes = $classInnerStmt->getAttributes();
                if (!isset($attributes['comments'])) {
                    $doc = null;
                    if (get_class($classInnerStmt) == 'PhpParser\Node\Stmt\Property') {
                        $doc = new Doc(generate_property_comment($classInnerStmt));
                    }
                    if (get_class($classInnerStmt) == 'PhpParser\Node\Stmt\ClassMethod') {
                        $doc = new Doc(generate_classMethod_comment($classInnerStmt));
                    }
                    if (isset($doc)) {
                        $classInnerStmt->setAttribute('comments', [$doc]);
                    }
                }
            }
        }
    }
    // output code with dummy comment.
    $code = $prettyPrinter->prettyPrint($stmts);
    echo "<?php ";
    if (!$withNamespace) {
        echo "\n";
        echo "\n";
    }

    echo $code;
} catch (Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}

function generate_class_comment(Class_ $class)
{
    $content = "/**\n";
    $content .= " * [{$class->name} description]\n";
    $content .= " */";
    return $content;
}

function generate_property_comment(Property $property)
{
    $content = "/**\n";
    $content .= " * [{$property->props[0]->name}]\n";
    $content .= " * @var [type]\n";
    $content .= " */";
    return $content;
}

function generate_classMethod_comment(ClassMethod $method)
{
    $content = "/**\n";
    $content .= " * [{$method->name} description]\n";
    foreach ($method->params as $param) {
        $type = $param->type ?: '[type]';
        $content .= " * @param  {$type}  {$param->name} [description]\n";
    }
    if ($method->name != "__construct") {
        $content .= " * @return [type]    [description]\n";
    }

    $content .= " */";
    return $content;
}
