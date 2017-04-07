<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * If statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class If_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The condition to enter this If.
     * @var \PhpTestBed\Node\NodeBaseAbstract
     */
    private $condition;

    /**
     * Indicates if the else block should be executed (if no conditions matches).
     * @var bool
     */
    private $elseRun;

    /**
     * Initializes object with a PhpParser If_ statemtent.
     * @param \PhpParser\Node\Stmt\If_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\If_ $node)
    {
        $this->elseRun = true;
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.if-enter2');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.if-exit');
    }

    /**
     * Prints a message indicating the condition status.
     */
    private function printIfCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.if-cond') . ' ' .
                $this->condition->getMessage()
        );
    }

    /**
     * Prints a message indicating no condition matches and will 
     * execute the else block.
     */
    private function printElseCond()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.else-cond')
                )
                , $this->node->else->getLine()
        );
    }

    /**
     * Resolves the if statement evaluating its condition and then 
     * crawling statements if true or else if false (if the else exists).
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();
        $this->condition = \PhpTestBed\Node\NodeLoader::load($this->node->cond);
        $this->printIfCond();
        $this->resolveIf();
        $this->resolveElse();
        $scriptCrawler->removeLevel();
    }

    /**
     * Resolves the main if and elseif until someone gets a 
     * true condition (or not).
     */
    private function resolveIf()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if ($this->condition->getResult()) {
            unset($this->condition);
            $scriptCrawler->crawl($this->node->stmts);
            $this->elseRun = false;
        } elseif (!empty($this->node->elseifs) && $this->elseRun) {
            unset($this->condition);
            foreach ($this->node->elseifs as $elseif) {
                if ($this->elseRun) {
                    $elseIf = new ElseIf_($elseif);
                    $this->elseRun = !$elseIf->getResolveStatus();
                }
            }
        }
    }

    /**
     * Resolves the else block in case of no condition matches
     */
    private function resolveElse()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if (!empty($this->node->else) && $this->elseRun) {
            $this->printElseCond();
            $scriptCrawler->crawl($this->node->else->stmts);
        }
    }

}
