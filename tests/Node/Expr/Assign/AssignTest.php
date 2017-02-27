<?php

namespace PhpTestBed_Test\Node\Expr\Assign;

class AssignTest extends \PhpTestBed_Test\TestCase
{

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->fileSource = __DIR__ . '\AssignSource.php';
        parent::__construct($name, $data, $dataName);
    }

    public function testVariableValue()
    {
        $actual = sprintf('%s: %s'
                , $this->i18n->get('code.line', [
                    'line' => 3
                ])
                , $this->i18n->get('code.assign', [
                    'var' => '$a',
                    'value' => 10
                ])
        );
        $this->assertEquals($actual, $this->messages[1]);
    }

    public function testVariableBitwise()
    {
        $actual = sprintf('%s: %s'
                , $this->i18n->get('code.line', [
                    'line' => 4
                ])
                , $this->i18n->get('code.assign-op', [
                    'var' => '$d',
                    'value' => $this->i18n->get('code.binary-op-var', [
                        'value' => 40,
                        'expr' => '($a &lt;&lt; 2)',
                        'where' => '$a = 10'
                    ])
                ])
        );
        $this->assertEquals($actual, $this->messages[2]);
    }

}
