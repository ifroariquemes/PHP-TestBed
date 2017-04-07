<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Node\NodeLoader;

class Array_ extends \PhpTestBed\Node\NodeExprAbstract
{

    private $items;

    public function __construct(\PhpParser\Node\Expr\Array_ $node)
    {
        parent::__construct($node);
    }

    private static function getArrayLine($key, $value)
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

    private static function getArrayTable($key, $value, $items)
    {
        return (\PhpTestBed\ScriptCrawler::getInstance()->getReturnMessage()) ?
                "[$items]" :
                <<<ARRAY_TABLE
<table class="testbed-array">
    <tr>
        <th>$key</th>
        <th>$value</th>
    </tr>
    $items
</table>
ARRAY_TABLE;
    }

    public static function prepareArrayToPrint($items)
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
            $arKeyPosition = I18n::getInstance()->get('legend.array-key-position');
            $arValue = I18n::getInstance()->get('legend.array-value');
            return self::getArrayTable($arKeyPosition, $arValue, substr($itemSt, 0, -2));
        } else {
            return I18n::getInstance()->get('legend.array-empty');
        }
    }

    public function getExpr()
    {
        return $this->prepareArrayToPrint($this->items);
    }

    public function getMessage()
    {
        return I18n::getInstance()->get('code.array', [
                    'value' => $this->getExpr()
        ]);
    }

    public function getResult()
    {
        return $this->items;
    }

    public function resolve()
    {
        foreach ($this->node->items as $item) {
            switch (get_class($item->value)) {
                case \PhpParser\Node\Expr\Array_::class:
                    $this->resolveArrayItem($item);
                    break;
                default:
                    $this->resolveItem($item, NodeLoader::load($item->value)->getResult());
                    break;
            }
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    private function resolveArrayItem(&$item)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = (new Array_($item->value))->getItems();
        } else {
            $this->items[] = (new Array_($item->value))->getItems();
        }
    }

    private function resolveItem(&$item, $value)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = $value;
        } else {
            $this->items[] = $value;
        }
    }

}
