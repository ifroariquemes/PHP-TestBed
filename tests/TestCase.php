<?php

namespace PhpTestBed_Test;

class TestCase extends \PHPUnit_Framework_TestCase
{

    protected $fileSource;
    protected $messages;

    /**
     * @var \PhpTestBed\I18n
     */
    protected $i18n;

    protected function setUp()
    {
        $this->messages = \PhpTestBed\ScriptCrawler::getInstance([
                    'script' => $this->fileSource,
                    'timestamp' => false,
                    'return' => true
                ])->run();
        $this->i18n = \PhpTestBed\I18n::getInstance();
    }

    public function testInicializacao()
    {
        $this->assertEquals($this->i18n->get('messages.start'), $this->messages[0]);
    }

    public function testFinalizacao()
    {
        $this->assertEquals($this->i18n->get('messages.end'), end($this->messages));
    }

}
