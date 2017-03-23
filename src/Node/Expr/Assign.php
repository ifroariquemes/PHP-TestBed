<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Repository;

class Assign extends \PhpTestBed\Node\ResolverAbstract
{

    private $varName;
    private $value;

    public function __construct(\PhpParser\Node\Expr\Assign $statement)
    {
        parent::__construct($statement);
    }

    public function getVarName()
    {
        return $this->varName;
    }

    public function getValue()
    {
        return $this->value;
    }

    private function printScalar($value)
    {
        $this->printMessage(I18n::getInstance()->get('code.assign', [
                    'var' => Stylizer::variable("\${$this->varName}"),
                    'value' => Stylizer::value($value)
        ]));
    }

    public static function prepareArrayToPrint($items)
    {
        $itemSt = '';
        if (!empty($items)) {
            foreach ($items as $key => $value) {
                $theKey = Stylizer::type($key);
                $theValue = (!is_array($value)) ? Stylizer::type($value) : self::prepareArrayToPrint($value);
                $itemSt .= <<<ARRAY_LINE
<tr>
    <td>$theKey</td>
    <td>$theValue</td>
</tr>
ARRAY_LINE;
            }
            $arKeyPosition = I18n::getInstance()->get('legend.array-key-position');
            $arValue = I18n::getInstance()->get('legend.array-value');
            return <<<ARRAY_TABLE
<table class="testbed-array">
    <tr>
        <th>$arKeyPosition</th>
        <th>$arValue</th>
    </tr>
    $itemSt
</table>
ARRAY_TABLE;
        } else {
            return I18n::getInstance()->get('legend.array-empty');
        }
    }

    private function printArray($items, $expr = null)
    {
        $codeArray = (is_null($expr)) ? 'code.array' : 'code.array-op';
        $this->printMessage(I18n::getInstance()->get('code.assign-op', [
                    'var' => Stylizer::variable("\${$this->varName}"),
                    'value' => I18n::getInstance()->get($codeArray, [
                        'value' => $this->prepareArrayToPrint($items),
                        'expr' => Stylizer::expression($expr)
                    ]),
        ]));
    }

    private function printOperation($expr)
    {
        $this->printMessage(I18n::getInstance()->get('code.assign-op', [
                    'var' => Stylizer::variable("\${$this->varName}"),
                    'value' => $expr
        ]));
    }

    private function printVariable($value, $refVar)
    {
        $left = Stylizer::variable("\${$this->varName}");
        $opSignal = Stylizer::operation("=");
        $right = Stylizer::variable("\${$refVar}");
        $valueSt = Stylizer::type($value);
        $expr = Stylizer::expression("($left $opSignal $right)");
        $bVar = [
            'value' => $valueSt,
            'expr' => $expr,
            'where' => "$right $opSignal $valueSt"
        ];
        $aVar = [
            'var' => $left,
            'value' => I18n::getInstance()->get('code.binary-op-var', $bVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.assign-op', $aVar));
    }

    protected function resolve()
    {
        $this->varName = $this->node->var->name;
        switch (get_class($this->node->expr)) {
            case \PhpParser\Node\Expr\Array_::class:
                $this->resolveArray();
                break;
            case \PhpParser\Node\Expr\PostInc::class:
            case \PhpParser\Node\Expr\PostDec::class:
                $this->resolvePostIncDec();
                break;
            case \PhpParser\Node\Expr\PreInc::class:
            case \PhpParser\Node\Expr\PreDec::class:
                $this->resolvePreIncDec();
                break;
            case \PhpParser\Node\Expr\Variable::class:
                $this->resolveVariable();
                break;
            default:
                if ($this->node->expr instanceof \PhpParser\Node\Scalar) {
                    $this->resolveScalar();
                } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\BinaryOp) {
                    $this->resolveBinaryOp();
                }
                break;
        }
        Repository::getInstance()->set($this->varName, $this->value);
    }

    private function resolveArray()
    {
        $this->value = (new Array_($this->node->expr))->getItems();
        $this->printArray($this->value);
    }

    private function resolveBinaryOp()
    {
        $bOp = new BinaryOp($this->node->expr);
        $this->printOperation($bOp->message());
        $this->value = $bOp->getResult();
    }

    private function resolveScalar()
    {
        $this->printScalar($this->node->expr->value);
        $this->value = $this->node->expr->value;
    }

    private function resolvePostIncDec()
    {
        $pValue = \PhpTestBed\Repository::getInstance()->get($this->node->expr->varName->name);
        $this->printVariable($pValue, $this->node->expr->varName->name);
        $this->value = $pValue;
        ($this->node->expr instanceof \PhpParser\Node\Expr\PostInc) ?
                        new PostInc($this->node->expr) : new PostDec($this->node->expr);
    }

    private function resolvePreIncDec()
    {
        $pID = ($this->node->expr instanceof \PhpParser\Node\Expr\PreInc) ?
                new PreInc($this->node->expr) : new PreDec($this->node->expr);
        $this->printVariable($this->varName, $pID->getValue(), $this->node->expr->varName->name);
        $this->value = $pID->getValue();
    }

    private function resolveVariable()
    {
        $currentValue = \PhpTestBed\Repository::getInstance()->get($this->node->expr->name);
        switch (gettype($currentValue)) {
            case 'array':
                $expr = sprintf('(%s %s %s)', Stylizer::variable("\$$this->varName"), Stylizer::operation('='), Stylizer::variable("\${$this->node->expr->name}"));
                $this->printArray($currentValue, $expr);
                break;
            default:
                $this->printVariable($currentValue, $this->node->expr->name);
                break;
        }
        Repository::getInstance()->set($this->varName, $currentValue);
    }

}
