<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * Do-While statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Do_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The loop condition.
     * @var \PhpTestBed\Node\NodeBaseAbstract 
     */
    private $condition;

    /**
     * Initializes object with a PhpParser Do_ statemtent.
     * @param \PhpParser\Node\Stmt\Do_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Do_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.do-while-enter');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.do-while-exit');
    }

    /**
     * Prints a message indicating the loop condition status.
     */
    protected function printLoopCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.loop-cond') . ' ' .
                $this->condition->getMessage(), $this->node->getAttribute('endLine')
        );
    }

    /**
     * Resolves the do-while statement crawling at firt glance all its statement
     * then verifies if the condition applies for a new iteration.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        if (!empty($this->node->stmts)) {
            do {
                $scriptCrawler->crawl($this->node->stmts);
                if ($scriptCrawler->getBreak() || $scriptCrawler->getThrow()) {
                    break;
                }
                $this->condition = \PhpTestBed\Node\NodeLoader::load($this->node->cond);
                $this->printLoopCond();
            } while ($this->condition->getResult());
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
