<?php

namespace PhpTestBed\Node;

use PhpTestBed\I18n;

/**
 * Base class for every PhpTestBed node.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
abstract class NodeBaseAbstract implements NodeBaseInterface
{

    /**
     * The node parsed with PhpParser.
     * @var \PhpParser\NodeAbstract
     */
    protected $node;

    /**
     * Initializes with a PhpParser statement node.
     * @param \PhpParser\NodeAbstract $statement The statement.
     */
    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->node = $statement;
        $this->printEnterMessage();
        $this->resolve();
    }

    /**
     * At the end of the object life, its final words will be printed.
     */
    public function __destruct()
    {
        $this->printExitMessage();
    }

    /**
     * Returns the line message using the statement line information.
     * @param int $overrideWithLine If not equal to 0, the line number will be override with this parameter value
     * @return string
     */
    final private function getLine(int $overrideWithLine = 0): string
    {
        if ($this->node instanceof \PhpParser\NodeAbstract) {
            return I18n::getInstance()->get('code.line'
                            , ['line' => (!$overrideWithLine) ? $this->node->getLine() : $overrideWithLine]) . ': ';
        }
    }

    /**
     * Prints a message with ScriptCrawler options.
     * @param string $message The message output
     * @param int $overrideWithLine If not equal to 0, the line number will be override with this parameter value
     */
    protected function printMessage(string $message, int $overrideWithLine = 0)
    {
        if (!empty($message)) {
            \PhpTestBed\ScriptCrawler::getInstance()->printMessage(
                    sprintf('%s%s'
                            , $this->getLine($overrideWithLine), $message)
            );
        }
    }

    /**
     * Print a special type message that is not part of the algorithm itself.
     * @param string $message The message output
     * @param int $overrideWithLine If not equal to 0, the line number will be override with this parameter value
     */
    final protected function printSystemMessage(string $message, int $overrideWithLine = 0)
    {
        $this->printMessage(
                \PhpTestBed\Stylizer::systemMessage($message), $overrideWithLine
        );
    }

    /**
     * A hidden method to print a starting message (when the statement is about
     * to be evaluated. It should be called only by the printEnterMessage method.
     * @param string $i18nCode The message code from a I18n file
     * @param int $overrideWithLine If not equal to 0, the line number will be override with this parameter value
     */
    protected function __printEnterMessage(string $i18nCode, int $overrideWithLine = 0)
    {
        $this->printSystemMessage(I18n::getInstance()->get($i18nCode), $overrideWithLine);
    }

    /**
     * A hidden method to print an ending message (when the statement is about
     * to be finished. It should be called only by the printExitMethod method.
     * @param string $i18nCode The message code from a I18n file
     * @param int $overrideWithLine If not equal to 0, the line number will be override with this parameter value
     */
    protected function __printExitMessage(string $i18nCode, int $overrideWithLine = 0)
    {
        if (!\PhpTestBed\ScriptCrawler::getInstance()->getThrow()) {
            $this->printSystemMessage(
                    I18n::getInstance()->get($i18nCode)
                    , ($overrideWithLine) ? $overrideWithLine : $this->node->getAttribute('endLine')
            );
        }
    }

    /**
     * This method is called at statement construction. So, if any message is
     * meant to be printed at this point, override the method and call
     * __printEnterMessage with apropriate arguments.
     */
    protected function printEnterMessage()
    {
        
    }

    /**
     * This method is called at statement destruction. So, if any message is
     * meant to be printed at this point, override the method and call
     * __printExitMessage with apropriate arguments.
     */
    protected function printExitMessage()
    {
        
    }

}
