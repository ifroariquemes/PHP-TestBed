<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Switch_ extends \PhpTestBed\Node\ResolverAbstract
{

    private $currentCase;
    private $currentCaseIndex;
    private $totalCases;
    private $condition;

    public function __construct(\PhpParser\Node\Stmt\Switch_ $node)
    {
        $this->currentCaseIndex = 0;
        $this->totalCases = count($node->cases);
        $this->condition = \PhpTestBed\Node\ResolverCondition::choose($node->cond);
        parent::__construct($node);
    }

    private function loadCurrentCase()
    {
        if ($this->currentCaseIndex >= $this->totalCases) {
            return false;
        }
        $this->currentCase = $this->node->cases[$this->currentCaseIndex];
        return true;
    }

    private function printSwitchEnterMessage()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-enter')
                )
        );
    }

    private function printSwitchExitMessage()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-exit')
                ), $this->node->getAttribute('endLine')
        );
    }

    private function printSwithCondMessage()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.switch-cond') . ' ' .
                $this->condition->message()
        );
    }

    private function printCaseDefaultMessage()
    {
        $this->printMessage(I18n::getInstance()
                        ->get('code.switch-case-default'));
    }

    private function printCaseSuccessMessage($caseValue)
    {
        $mVar = ['value' => $caseValue, 'cond' => $this->condition->getExpr()];
        $this->printMessage(
                I18n::getInstance()->get('code.switch-case-success', $mVar)
                , $this->currentCase->getLine()
        );
    }

    private function printCaseFailMessage($caseValue)
    {
        $mVar = ['value' => $caseValue, 'cond' => $this->condition->getExpr()];
        $this->printMessage(
                I18n::getInstance()->get('code.switch-case-fail', $mVar)
                , $this->currentCase->getLine()
        );
    }

    private function printCaseEnter($caseLine)
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-case-enter')
                ), $caseLine
        );
    }

    private function printCaseExit($caseLine)
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.switch-case-exit')
                ), $caseLine
        );
    }

    protected function resolve()
    {
        $this->printSwitchEnterMessage();
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
        $this->printSwitchExitMessage();
    }

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
                    $binOp = \PhpTestBed\Node\ResolverCondition::choose($this->currentCase->cond);
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
