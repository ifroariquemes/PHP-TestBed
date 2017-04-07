<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Switch-Case statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Switch_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The current case beign evaluated.
     * @var \PhpParser\Node\Stmt\Case_
     */
    private $currentCase;

    /**
     * The current case index.
     * @var int
     */
    private $currentCaseIndex;

    /**
     * Total cases inside the switch.
     * @var int 
     */
    private $totalCases;

    /**
     * The condition that will generate a result to be checked if it matches
     * any case value.
     * @var \PhpTestBed\Node\NodeBaseAbstract
     */
    private $condition;

    /**
     * Initializes object with a PhpParser Switch_ statemtent.
     * @param \PhpParser\Node\Stmt\Switch_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Switch_ $node)
    {
        $this->currentCaseIndex = 0;
        $this->totalCases = count($node->cases);
        $this->condition = \PhpTestBed\Node\NodeLoader::load($node->cond);
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.switch-enter');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.switch-exit');
    }

    /**
     * Puts the case at currentCaseIndex on respective attribute currentCase
     * @return boolean
     */
    private function loadCurrentCase(): bool
    {
        if ($this->currentCaseIndex >= $this->totalCases) {
            return false;
        }
        $this->currentCase = $this->node->cases[$this->currentCaseIndex];
        return true;
    }

    /**
     * Prints the condition message showing what value is expected.
     */
    private function printSwithCondMessage()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.switch-cond') . ' ' .
                $this->condition->getMessage()
        );
    }

    /**
     * Prints a message indicating that the default was reach.
     */
    private function printCaseDefaultMessage()
    {
        $this->printMessage(I18n::getInstance()
                        ->get('code.switch-case-default'));
    }

    /**
     * Prints a message indicating that the currentCase matches it value
     * with the switch condition result.
     * @param mixed $caseValue The case value
     */
    private function printCaseSuccessMessage($caseValue)
    {
        $mVar = ['value' => $caseValue, 'cond' => $this->condition->getExpr()];
        $this->printMessage(
                I18n::getInstance()->get('code.switch-case-success', $mVar)
                , $this->currentCase->getLine()
        );
    }

    /**
     * Prints a message indicating that the currentCase not matches it value
     * with the switch condition result.
     * @param mixed $caseValue The case value
     */
    private function printCaseFailMessage($caseValue)
    {
        $mVar = ['value' => $caseValue, 'cond' => $this->condition->getExpr()];
        $this->printMessage(
                I18n::getInstance()->get('code.switch-case-fail', $mVar)
                , $this->currentCase->getLine()
        );
    }

    /**
     * Prints the starter message for a case statement.
     * @param int $caseLine Line where the case starts
     */
    private function printCaseEnter(int $caseLine)
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-case-enter')
                ), $caseLine
        );
    }

    /**
     * Prints the exit message for a case statement.
     * @param int $caseLine Line where the case ends
     */
    private function printCaseExit(int $caseLine)
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-case-exit')
                ), $caseLine
        );
    }

    /**
     * Resolves the switch statement evaluating its condition result and then
     * looks for a case value that matches it.
     */
    public function resolve()
    {
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        $this->printSwithCondMessage();
        while ($this->loadCurrentCase()) {
            $this->crawlSwitch();
            $this->currentCaseIndex++;
            if (\PhpTestBed\ScriptCrawler::getInstance()->getBreak()) {
                break;
            }
        }
        \PhpTestBed\ScriptCrawler::getInstance()->removeBreak();
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
    }

    /**
     * Evaluates if the currentCase value matches the switch condition result
     * or if default was reach.
     */
    private function crawlSwitch()
    {
        switch ($this->currentCase->cond) {
            case null: // default
                $this->printCaseDefaultMessage();
                $this->crawlCaseStmts();
                break;
            default:
                if ($this->currentCase->cond instanceof \PhpParser\Node\Scalar) {
                    $caseValue = $this->currentCase->cond->value;
                } else {
                    $binOp = \PhpTestBed\Node\NodeLoader::load($this->currentCase->cond);
                    $caseValue = $binOp->getResult();
                }
                if ($this->condition->getResult() == $caseValue) {
                    $this->printCaseSuccessMessage($caseValue);
                    $this->crawlCaseStmts();
                } else {
                    $this->printCaseFailMessage($caseValue);
                }
                break;
        }
    }

    /**
     * Evaluates if a two or more cases shares the same block and select
     * the right set of statements to crawl in case of a case matches 
     * the switch value.
     */
    private function crawlCaseStmts()
    {
        $case = $this->node->cases[$this->currentCaseIndex];
        $this->printCaseEnter($case->getLine());
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        while (!count($case->stmts) && $this->currentCaseIndex < $this->totalCases) {
            $case = $this->node->cases[++$this->currentCaseIndex];
        }
        \PhpTestBed\ScriptCrawler::getInstance()->crawl($case->stmts);
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
        $this->printCaseExit($case->getAttribute('endLine'));
    }

}
