<?php namespace dummy;

use PhpParser\Lexer;
use PhpParser\Parser\Tokens;

class KeepOriginalValueLexer extends Lexer // or Lexer\Emulative
{
    public function getNextToken(&$value = null, &$startAttributes = null, &$endAttributes = null) {
        $tokenId = parent::getNextToken($value, $startAttributes, $endAttributes);

        if ($tokenId == Tokens::T_CONSTANT_ENCAPSED_STRING // non-interpolated string
            || $tokenId == Tokens::T_LNUMBER               // integer
            || $tokenId == Tokens::T_DNUMBER               // floating point number
        ) {
            // could also use $startAttributes, doesn't really matter here
            $endAttributes['originalValue'] = $value;
        }

        return $tokenId;
    }
}