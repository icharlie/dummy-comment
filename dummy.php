<?php

class dummy
{
    private $noCommentVar;

    /**
     * [$withCommentVar description]
     * @var [type]
     */
    private $withCommentVar;


    private function noComment (
        $a = [],
        array $b = []
    ) {
        $content = "/**\n";
        $content .= " * [{$method->name} description]\n";
    }


    /**
     * With comment function
     */
    public function withComment()
    {
    }
}
