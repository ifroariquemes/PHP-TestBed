<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Node\NodeLoader;

/**
 * An array with its items.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Array_ extends \PhpTestBed\Node\NodeExprAbstract
{

    /**
     * The array items.
     * @var array
     */
    private $items;

    /**
     * Initializes object with a PhpParser Array_ statemtent.
     * @param \PhpParser\Node\Expr\Array_ $node The statement
     */
    public function __construct(\PhpParser\Node\Expr\Array_ $node)
    {
        $this->items = array();
        parent::__construct($node);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return $this->prepareArrayToPrint($this->items);
    }

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return I18n::getInstance()->get('code.array', [
                    'value' => $this->getExpr()
        ]);
    }

    /**
     * Returns the array items (alias for Array_::getItems()).
     * @return array
     * @see Array_::getItems()
     */
    public function getResult(): array
    {
        return $this->getItems();
    }

    /**
     * Returns the array items.
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Puts each item within the Array_ statement inside $this->items.
     */
    public function resolve()
    {
        foreach ($this->node->items as $item) {
            if ($item->value instanceof \PhpParser\Node\Expr\Array_) {
                $this->resolveArrayItem($item);
            } else {
                $this->resolveItem($item, NodeLoader::load($item->value)->getResult());
            }
        }
    }

    /**
     * Executed when an item of Array_ statement is another Array_ statement.
     * @param \PhpParser\Node\Expr\ArrayItem $item The ArrayItem
     */
    private function resolveArrayItem(\PhpParser\Node\Expr\ArrayItem &$item)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = (new Array_($item->value))->getItems();
        } else {
            $this->items[] = (new Array_($item->value))->getItems();
        }
    }

    /**
     * Executed when the item is a common value.
     * @param \PhpParser\Node\Expr\ArrayItem $item The ArrayItem
     * @param mixed $value The ArrayItem value
     */
    private function resolveItem(\PhpParser\Node\Expr\ArrayItem &$item, $value)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = $value;
        } else {
            $this->items[] = $value;
        }
    }

    /**
     * Returns a HTML table with array keys and values information.
     * @param mixed $items The array items
     * @return string
     */
    public static function prepareArrayToPrint($items): string
    {
        $itemSt = '';
        if (!empty($items)) {
            foreach ($items as $key => $value) {
                $theKey = Stylizer::type($key);
                $theValue = (!is_array($value)) ?
                        Stylizer::type($value) :
                        self::prepareArrayToPrint($value);
                $itemSt .= self::getArrayLine($theKey, $theValue);
            }
            return self::getArrayTable(substr($itemSt, 0, -2));
        } else {
            return I18n::getInstance()->get('legend.array-empty');
        }
    }

    /**
     * Returns a HTML table line with the pair key => value.
     * @param mixed $key The item key
     * @param mixed $value The item value
     * @return string
     */
    private static function getArrayLine($key, $value): string
    {
        return (\PhpTestBed\ScriptCrawler::getInstance()->getReturnMessage()) ?
                "$key => $value, " :
                <<<ARRAY_LINE
<tr>
    <td>$key</td>
    <td>$value</td>
</tr>  
ARRAY_LINE;
    }

    /**
     * Returns a HTML table with array items information.
     * @param string $items HTML with table lines
     * @return string
     * @see Array_::getArrayLine()
     */
    private static function getArrayTable(string $items): string
    {
        $keyLegend = I18n::getInstance()->get('legend.array-key-position');
        $valueLegend = I18n::getInstance()->get('legend.array-value');
        return (\PhpTestBed\ScriptCrawler::getInstance()->getReturnMessage()) ?
                "[$items]" :
                <<<ARRAY_TABLE
<table class="testbed-array">
    <tr>
        <th>$keyLegend</th>
        <th>$valueLegend</th>
    </tr>
    $items
</table>
ARRAY_TABLE;
    }

}
