<?php

use Keoghan\Lookahead\IsChained;

class Dummy
{
    public function something()
    {
        if ((new IsChained)->check()) {
            return $this;        
        };
        
        return 'Not Chained';
    }

    public function somethingElse()
    {
        return 'Chained';
    }
}
