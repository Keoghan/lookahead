<?php

namespace Keoghan\Lookahead;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\ParserFactory;

class IsChained
{
    protected $methodName;
    protected $fileName;
    protected $lineNumber;

    public function check()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $code = $this->analyseTrace($trace);
        $parsed = $this->parseCode($code);

        return $this->hasChainedMethod($parsed[0]);
    }

    protected function analyseTrace($trace)
    {
        $this->fileName = $trace[1]['file'];
        $this->lineNumber = $trace[1]['line'];
        $this->methodName = $trace[1]['function'];

        $code = '';
        $prepend = '';
        $fileContent = file($this->fileName);

        //check before to EOL
        $line = $this->lineNumber - 2;
        while (strpos($prepend, ';') === false && strpos($prepend, '{') === false && $line >=0) {
            $prepend = $fileContent[$line] . $prepend;
            $line --;
        }
        $prepend = substr($prepend, strpos($prepend, ';') + 1);
        $prepend = substr($prepend, strpos($prepend, '{') + 1);

        //check after to EOL
        $line = $this->lineNumber - 1;
        while (strpos($code, ';') === false && $line <= sizeof($fileContent)) {
            $code .= $fileContent[$line];
            $line ++;
        }

        return $prepend . $code;
    }

    protected function parseCode($code)
    {
        $code = (substr($code, 0, 5) != '<?php' ? '<?php ' : '') . $code;
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        return $parser->parse($code);
    }

    protected function hasChainedMethod($node)
    {
        if (! method_exists($node, 'getSubNodeNames')) {
            return false;
        }

        foreach ($node->getSubNodeNames() as $subnodeName) {
            $seek = $node->{$subnodeName};
            $seek = is_array($seek) ? $seek : [$seek];

            foreach ($seek as $subnode) {
                if ($this->chainsOntoMyMethod($subnode)) {
                    return true;
                }

                if ($this->hasChainedMethod($subnode)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function isMyMethod($node)
    {
        return $node instanceof MethodCall
            && $node->name == $this->methodName;
    }

    protected function chainsOntoMyMethod($node)
    {
        return $node instanceof MethodCall
            && $this->isMyMethod($node->var);
    }
}
