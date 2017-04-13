<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * For statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class For_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser For_ statemtent.
     * @param \PhpParser\Node\Stmt\For_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\For_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Return the result of testing all iteration conditions.
     * @return boolean
     */
    private function testConditions(): bool
    {
        foreach ($this->node->cond as $cond) {
            $binOp = \PhpTestBed\Node\NodeLoader::load($cond);
            $this->printMessage(
                    I18n::getInstance()->get('code.if-cond') . ' ' .
                    $binOp->getMessage()
            );
            if ($binOp->getResult() === false) {
                unset($binOp);
                return false;
            }
        }
        unset($binOp);
        return true;
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.for-enter');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.for-exit');
    }

    /**
     * Resolves the for statement crawling its initial statements, then
     * while conditions are good, crawls its internal statements and its
     * loop statements.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();
        $scriptCrawler->crawl($this->node->init);
        while (!$scriptCrawler->getBreak() && !$scriptCrawler->getThrow() && $this->testConditions()) {
            if (!empty($this->node->stmts)) {
                $scriptCrawler->crawl($this->node->stmts);
            }
            if (!empty($this->node->loop)) {
                $scriptCrawler->crawl($this->node->loop);
            }
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
