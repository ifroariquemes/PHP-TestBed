<?php

namespace PhpTestBed\Node\Expr;

class Array_ extends \PhpTestBed\Node\ResolverAbstract
{

    private $items;

    public function __construct(\PhpParser\Node\Expr\Array_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        foreach ($this->node->items as $item) {
            switch (get_class($item->value)) {
                case \PhpParser\Node\Expr\Array_::class:
                    $this->resolveArrayItem($item);
                    break;
                default:
                    if ($item->value instanceof \PhpParser\Node\Scalar) {
                        $this->resolveScalarItem($item);
                    } else {
                        $this->resolveLiteralValue($item, \PhpTestBed\Node\ResolverCondition::choose($item->value)->getResult());
                    }
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

    private function resolveScalarItem(&$item)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = $item->value->value;
        } else {
            $this->items[] = $item->value->value;
        }
    }

    private function resolveLiteralValue(&$item, $value)
    {
        if (!is_null($item->key)) {
            $this->items[$item->key->value] = $value;
        } else {
            $this->items[] = $value;
        }
    }

}
