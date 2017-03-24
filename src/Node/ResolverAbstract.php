<?php

namespace PhpTestBed\Node;

use PhpTestBed\I18n;

abstract class ResolverAbstract
{

    /**
     * @var \PhpParser\NodeAbstract
     */
    protected $node;

    /**
     * @var ResolverAbstract
     */
    protected $parentNode;
    protected $resolveState;

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->node = $statement;
        $this->printEnterMessage();
        $this->resolve();
        $this->printExitMessage();
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

    final protected function printMessage($message, $overrideWithLine = 0)
    {
        if (!empty($message)) {
            \PhpTestBed\ScriptCrawler::getInstance()->printMessage(
                    sprintf('%s%s'
                            , $this->getLine($overrideWithLine), $message)
            );
        }
    }

    final protected function printSystemMessage($message, $overrideWithLine = 0)
    {
        $this->printMessage(
                \PhpTestBed\Stylizer::systemMessage($message), $overrideWithLine
        );
    }

    protected function __printEnterMessage($i18nCode, $overrideWithLine = 0)
    {
        $this->printSystemMessage(I18n::getInstance()->get($i18nCode), $overrideWithLine);
    }

    protected function __printExitMessage($i18nCode, $overrideWithLine = 0)
    {
        if (!\PhpTestBed\ScriptCrawler::getInstance()->getThrow()) {
            $this->printSystemMessage(
                    I18n::getInstance()->get($i18nCode)
                    , ($overrideWithLine) ? $overrideWithLine : $this->node->getAttribute('endLine')
            );
        }
    }

    protected function printEnterMessage()
    {
        
    }

    protected function printExitMessage()
    {
        
    }

    protected function resolve()
    {
        throw new Exception('Class ' . __CLASS__ . ' must implements its ::resolve() method.');
    }

}
