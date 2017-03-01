<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Echo_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Echo_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        foreach ($this->node->exprs as $expr) {
            if ($expr instanceof \PhpParser\Node\Scalar\Encapsed) {
                $enc = new \PhpTestBed\Node\Scalar\Encapsed($expr);
                $eVar = [
                    'value' => Stylizer::value($enc->getResult()),
                    'expr' => Stylizer::expression($enc->getExpr()),
                    'where' => \PhpTestBed\Repository::showUsed([], $enc->getUsedVars())
                ];
                $mVar = [
                    'value' => I18n::getInstance()->get('code.binary-op-var', $eVar)
                ];
                $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
            } elseif ($expr instanceof \PhpParser\Node\Scalar) {
                $mVar = [
                    'value' => Stylizer::value($expr->value)
                ];
                $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
            } else {
                $line = new \PhpTestBed\Node\Expr\BinaryOp($expr);
                $this->printMessage(sprintf('%s %s', I18n::getInstance()->get('code.echo'), $line->message()));
            }
        }
    }

}
