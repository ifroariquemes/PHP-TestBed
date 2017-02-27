<?php

namespace PhpTestBed;

use PhpParser\ParserFactory;

class ScriptCrawler
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    private $script;
    private $path;
    private $parser;
    private $nodes;
    private $level;
    private $useTimestamp;
    private $returnMessages;
    private $messages;

    public function __construct($options)
    {
        $this->path = (is_string($options)) ? $options : $options['script'];
        if (!\file_exists($this->path)) {
            throw new \Exception(I18n::getInstance()
                    ->get('exceptions.script-not-found'
                            , ['script' => $this->path]));
        }
        $this->script = file_get_contents($this->path);
        $this->level = 0;
        $this->messages = array();
        $this->useTimestamp = (is_array($options)) ? $options['timestamp'] ?? true : true;
        $this->returnMessages = (is_array($options)) ? $options['return'] ?? false : false;
        $this->parseScript();
    }

    private function parseScript()
    {
        ini_set('xdebug.max_nesting_level', 3000);
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->nodes = $this->parser->parse($this->script);
    }

    public function run()
    {
        $this->printMessage(I18n::getInstance()->get('messages.start'));
        $this->crawl($this->nodes);
        $this->printMessage(I18n::getInstance()->get('messages.end'));
        var_dump(Repository::getInstance()->getRepository());
        var_dump($this->nodes);
        if ($this->returnMessages) {
            return $this->messages;
        }
    }

    public function crawl(array $nodes)
    {
        foreach ($nodes as $node) {
            $nodeClass = str_replace('PhpParser\\', 'PhpTestBed\\', get_class($node));
            if (!class_exists($nodeClass)) {
                $this->printMessage(
                        Stylizer::systemException(
                                I18n::getInstance()->get('exceptions.node-not-implemented'
                                        , ['node' => $nodeClass])
                        )
                );
            } else {
                new $nodeClass($node);
            }
        }
    }

    public function printMessage($message)
    {
        if (!empty($message)) {
            $millis = microtime(true);
            $micro = sprintf("%06d", ($millis - floor($millis)) * 1E6);
            $timestamp = new \DateTime(date('Y-m-d H:i:s.' . $micro, $millis));
            if (!$this->returnMessages) {
                $spaces = str_repeat('<span class="testbed-level"></span>', $this->level);
                echo ($this->useTimestamp) ?
                        sprintf('<span class="testbed-timestamp">[%s]</span> <span class="testbed-message">%s%s</span><br>'
                                , $timestamp->format(
                                        I18n::getInstance()->get('config.timestamp')
                                ), $spaces, $message) :
                        sprintf('<span class="testbed-message">%s%s</span><br>'
                                , $spaces, $message);
            } else {
                $spaces = str_repeat('\t', $this->level);
                $message = ($this->useTimestamp) ?
                        sprintf('[%s] %s%s'
                                , $timestamp->format(
                                        I18n::getInstance()->get('config.timestamp')
                                ), $spaces, strip_tags($message)) :
                        sprintf('%s%s'
                                , $spaces, strip_tags($message));
                array_push($this->messages, $message);
            }
        }
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function addLevel()
    {
        $this->level++;
    }

    public function removeLevel()
    {
        if ($this->level === 0) {
            throw new Exception("Level cannot be reduced below zero.");
        }
        $this->level--;
    }

}
