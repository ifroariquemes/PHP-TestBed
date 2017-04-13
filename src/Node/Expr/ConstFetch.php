<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

/**
 * Used when fetching a constant value.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class ConstFetch extends \PhpTestBed\Node\NodeUsableAbstract
{

    /**
     * The constant name.
     * @var string
     */
    private $constName;

    /**
     * Initializes object with a PhpParser ConstFetch statemtent.
     * @param \PhpParser\Node\Expr\ConstFetch $node The statement
     */
    public function __construct(\PhpParser\Node\Expr\ConstFetch $node)
    {
        $this->constName = $node->name->parts[0];
        parent::__construct($node);
    }

    /**
     * Resolves the ConstFetch statement getting the its value from Repository.
     */
    public function resolve()
    {
        switch ($this->constName) {
            case 'true':
                $this->result = true;
                break;
            case 'false':
                $this->result = false;
                break;
            default:
                $this->result = \PhpTestBed\Repository::getInstance()->getConst($this->constName);
                break;
        }
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return Stylizer::constant($this->constName);
    }

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return I18n::getInstance()->get('code.binary-op-var', [
                    'expr' => Stylizer::expression($this->getExpr()),
                    'value' => Stylizer::value($this->result),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

    /**
     * Adds the use of this constant at current script line execution.
     */
    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedConstant($this->constName, $this->result);
    }

}
