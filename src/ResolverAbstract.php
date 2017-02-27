<?php

namespace PhpTestBed;

abstract class ResolverAbstract
{

    /**
     * @var \PhpParser\NodeAbstract
     */
    protected $node;
    protected $resolveState;

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->node = $statement;
        $this->resolve();
    }

    final public function setResolveState($resolveState)
    {
        $this->resolveState = $resolveState;
    }

    final public function getResolveState()
    {
        return $this->resolveState;
    }

    final private function getLine($overrideWithLine = 0)
    {
        if ($this->node instanceof \PhpParser\NodeAbstract) {
            return I18n::getInstance()->get('code.line'
                            , ['line' => (!$overrideWithLine) ? $this->node->getLine() : $overrideWithLine]) . ': ';
        }
    }

    final public function printMessage($message, $overrideWithLine = 0)
    {
        if (!empty($message)) {
            ScriptCrawler::getInstance()->printMessage(
                    sprintf('%s%s'
                            , $this->getLine($overrideWithLine), $message)
            );
        }
    }

    protected function resolve()
    {
        throw new \Exception("Class must contain ::resolve() method");
    }

}
