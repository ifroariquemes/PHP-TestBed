<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * Else-If statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class ElseIf_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * The condition to enter this If
     * @var \PhpTestBed\Node\NodeBaseAbstract
     */
    private $condition;

    /**
     * Stores the condition result
     * @var bool
     */
    private $resolveStatus;

    /**
     * Initializes object with a PhpParser ElseIf_ statemtent.
     * @param \PhpParser\Node\Stmt\ElseIf_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\ElseIf_ $node)
    {
        parent::__construct($node);
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
     * Resolves the else-if statement evaluating its condition and then 
     * crawling statements if true.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $this->condition = \PhpTestBed\Node\NodeLoader::load($this->node->cond);
        $this->printIfCond();
        $this->resolveStatus = $this->condition->getResult();
        if ($this->resolveStatus) {
            unset($this->condition);
            $scriptCrawler->crawl($this->node->stmts);
        }
    }

    /**
     * Returns the condition result
     * @return bool
     */
    function getResolveStatus(): bool
    {
        return $this->resolveStatus;
    }

}
