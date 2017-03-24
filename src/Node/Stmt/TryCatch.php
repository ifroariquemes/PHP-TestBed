<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class TryCatch extends \PhpTestBed\Node\ResolverAbstract
{

    private $enterLevel;

    public function __construct(\PhpParser\Node\Stmt\TryCatch $node)
    {
        parent::__construct($node);
    }

    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.try-enter');
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $this->enterLevel = $scriptCrawler->getLevel();
        $scriptCrawler->addLevel();
        $scriptCrawler->registerTry($this);
        $scriptCrawler->crawl($this->node->stmts);
        $scriptCrawler->unregisterTry();
        $scriptCrawler->removeThrow();
    }

    public function resolveCatch(\PhpParser\Node\Stmt\Throw_ $throw)
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        while ($scriptCrawler->getLevel() != $this->enterLevel) {
            $scriptCrawler->removeLevel(true);
        }
        $this->printSystemMessage(I18n::getInstance()->get('code.try-exit'), $this->node->getAttribute('endLine'));
        if (!empty($this->node->catches)) {
            foreach ($this->node->catches as $catch) {
                $throwNs = $this->getNamespaceFromParts($throw->expr->class->parts);
                $throwObject = new $throwNs;
                $catchNs = $this->getNamespaceFromParts($catch->types[0]->parts);
                $mVar = [
                    'throw' => Stylizer::expression(
                            Stylizer::classRef($throwNs)
                    ),
                    'catch' => Stylizer::expression(
                            Stylizer::classRef($catchNs)
                    )
                ];
                if ($throwObject instanceof $catchNs) {
                    $this->printMessage(I18n::getInstance()->get('code.try-catch-check-ok', $mVar), $catch->getAttribute('startLine'));
                    $this->printSystemMessage(I18n::getInstance()->get('code.try-catch-enter'), $catch->getAttribute('startLine'));
                    $scriptCrawler->addLevel(true);
                    $scriptCrawler->crawl($catch->stmts, true);
                    $scriptCrawler->removeLevel(true);
                    $this->printSystemMessage(I18n::getInstance()->get('code.try-catch-exit'), $catch->getAttribute('endLine'));
                } else {
                    $this->printMessage(I18n::getInstance()->get('code.try-catch-check-fail', $mVar), $catch->getAttribute('startLine'));
                }
            }
        }
        $this->resolveFinally();
    }

    private function resolveFinally()
    {
        if (!empty($this->node->finally)) {
            $this->printSystemMessage(I18n::getInstance()->get('code.try-finally-enter'), $this->node->finally->getAttribute('startLine'));
            $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
            $scriptCrawler->addLevel(true);
            \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->finally->stmts, true);
            $scriptCrawler->removeLevel(true);
            $this->printSystemMessage(I18n::getInstance()->get('code.try-finally-exit'), $this->node->finally->getAttribute('endLine'));
        }
    }

    private function getNamespaceFromParts($parts)
    {
        $strNs = '';
        foreach ($parts as $part) {
            $strNs .= "\\$part";
        }
        return $strNs;
    }

}
