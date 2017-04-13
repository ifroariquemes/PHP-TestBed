<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Node\Expr\Array_;

/**
 * Foreach statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Foreach_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Foreach_ statemtent.
     * @param \PhpParser\Node\Stmt\Foreach_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Foreach_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.foreach-enter');
    }

    /**
     * Prints the exit message.
     */
    protected function printExitMessage()
    {
        parent::__printExitMessage('code.foreach-exit');
    }

    /**
     * Prints a message showing the attribution of the next iteration value.
     * @param mixed $value The value
     */
    private function printNextItem($value)
    {
        $mVar = [
            'var' => Stylizer::variable($this->node->valueVar->name),
            'origin' => Stylizer::variable($this->node->expr->name),
            'value' => (!is_array($value)) ? Stylizer::value($value) : Array_::prepareArrayToPrint($value)
        ];
        $this->printMessage(I18n::getInstance()->get('code.foreach-next-item', $mVar));
    }

    /**
     * Prints a message showing the attribution of the next iteration key.
     * @param mixed $key The key
     */
    private function printNextKey($key)
    {
        $mVar = [
            'var' => Stylizer::variable($this->node->keyVar->name),
            'origin' => Stylizer::variable($this->node->expr->name),
            'value' => Stylizer::value($key)
        ];
        $this->printMessage(I18n::getInstance()->get('code.foreach-next-key', $mVar));
    }

    /**
     * Resolves the foreach statement getting sequentially each value and key
     * of the given collection and putting it on specified variables.
     * With these, crawls the internal statements until map all collection.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();
        $expr = \PhpTestBed\Repository::getInstance()->get($this->node->expr->name);
        foreach ($expr as $key => $value) {
            if ($scriptCrawler->getBreak() || $scriptCrawler->getThrow()) {
                break;
            }
            if (!is_null($this->node->keyVar)) {
                \PhpTestBed\Repository::getInstance()->set($this->node->keyVar->name, $key);
                $this->printNextKey($key);
            }
            \PhpTestBed\Repository::getInstance()->set($this->node->valueVar->name, $value);
            $this->printNextItem($value);
            $scriptCrawler->crawl($this->node->stmts);
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
