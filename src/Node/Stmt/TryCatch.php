<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Try-Catch-Finally statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class TryCatch extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The ScriptCrawler nesting level before getting into try-catch-finally
     * @var int
     */
    private $enterLevel;

    /**
     * Initializes object with a PhpParser TryCatch statemtent.
     * @param \PhpParser\Node\Stmt\TryCatch $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\TryCatch $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.try-enter');
    }

    /**
     * Resolves the try-catch-finally statement registering this object
     * into ScriptCrawler try array and crawling its internal statements.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $this->enterLevel = $scriptCrawler->getLevel();
        $scriptCrawler->addLevel();
        $scriptCrawler->registerTry($this);
        $scriptCrawler->crawl($this->node->stmts);
        $scriptCrawler->unregisterTry();
        $scriptCrawler->removeThrow();
    }

    /**
     * If a exception is triggered then it will look for a catch that treat
     * that exception class and execute finally block if exists.
     * @param \PhpParser\Node\Stmt\Throw_ $throw Throw that triggered the exception
     */
    public function resolveCatch(\PhpParser\Node\Stmt\Throw_ $throw)
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        while ($scriptCrawler->getLevel() != $this->enterLevel) {
            $scriptCrawler->removeLevel(true);
        }
        $this->printSystemMessage(I18n::getInstance()->get('code.try-exit'), $this->node->getAttribute('endLine'));
        if (!empty($this->node->catches)) {
            $this->crawlCatch($throw);
        }
        $this->resolveFinally();
    }

    /**
     * Try to find a catch that treat the exception class from the given throw
     * @param \PhpParser\Node\Stmt\Throw_ $throw Throw that triggered the exception
     */
    private function crawlCatch(\PhpParser\Node\Stmt\Throw_ &$throw)
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        foreach ($this->node->catches as $catch) {
            $throwNs = $this->getNamespaceFromParts($throw->expr->class->parts);
            $throwObject = new $throwNs;
            $catchNs = $this->getNamespaceFromParts($catch->types[0]->parts);
            $mVar = [
                'throw' => Stylizer::expression(Stylizer::classRef($throwNs)),
                'catch' => Stylizer::expression(Stylizer::classRef($catchNs))
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

    /**
     * If finally block exists, runs its statements
     */
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

    /**
     * Builds the complete namespace from parts 
     * @param string[] $parts Parts with namespace information
     * @return string
     */
    private function getNamespaceFromParts(array $parts): string
    {
        $strNs = '';
        foreach ($parts as $part) {
            $strNs .= "\\$part";
        }
        return $strNs;
    }

}
