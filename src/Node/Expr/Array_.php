<?php

namespace PhpTestBed\Node\Expr;

class Array_ extends \PhpTestBed\ResolverAbstract
{

    private $items;

    public function __construct(\PhpParser\Node\Expr\Array_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        foreach ($this->node->items as $item) {
            if ($item->value instanceof \PhpParser\Node\Scalar) {
                if (!is_null($item->key)) {
                    $this->items[$item->key->value] = $item->value->value;
                } else {
                    $this->items[] = $item->value->value;
                }
            }
        }
    }

    public function getItems()
    {
        return $this->items;
    }

}
