<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * While statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class While_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The loop condition.
     * @var \PhpTestBed\Node\NodeBaseAbstract 
     */
    private $condition;

    /**
     * Initializes object with a PhpParser While_ statemtent.
     * @param \PhpParser\Node\Stmt\While_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\While_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.while-enter');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.while-exit');
    }

    /**
     * Prints a message indicating the loop condition status.
     */
    private function printLoopCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.loop-cond') . ' ' .
                $this->condition->getMessage()
        );
    }

    /**
     * Resolves the while statement verifying if the condition applies 
     * for a new iteration and, if true, crawls its statements then repeats
     * until it is false.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();

        $this->condition = \PhpTestBed\Node\NodeLoader::load($this->node->cond);
        $this->printLoopCond();
        while ($this->condition->getResult()) {
            unset($this->condition);
            $scriptCrawler->crawl($this->node->stmts);
            if ($scriptCrawler->getBreak() || $scriptCrawler->getThrow()) {
                break;
            }
            $this->condition = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
            $this->printLoopCond();
        }

        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
