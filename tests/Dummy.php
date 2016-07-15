<?php 

use Lookahead\IsChained;

class Dummy
{
    public function something()
    {
        if (! (new IsChained)->check()) {
            return 'Not Chained';
        };

        return $this;
    }

    public function somethingElse()
    {
        return 'Chained';
    }
}
