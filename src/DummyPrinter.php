<?php namespace dummy;

use PhpParser\Node\Scalar;
use PhpParser\PrettyPrinter\Standard;

class DummyPrinter extends Standard
{
    public function pScalar_String(Scalar\String_ $node) {
        if ($node->getAttribute('originalValue')) {
            return $this->pNoIndent($node->getAttribute('originalValue'));
        }
        return parent::pScalar_String($node);
    }
}