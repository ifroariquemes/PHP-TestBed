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
    private $break;
    private $try;
    private $throw;

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
        $this->try = array();
        $this->useTimestamp = (is_array($options)) ? $options['timestamp'] ?? true : true;
        $this->returnMessages = (is_array($options)) ? $options['return'] ?? false : false;
        $this->break = false;
        $this->throw = false;
        $this->parseScript();
    }

    private function parseScript()
    {
        ini_set('xdebug.max_nesting_level', 3000);
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->nodes = $this->parser->parse($this->script);
    }

    private function printEnterMessage()
    {
        $this->printMessage(I18n::getInstance()->get('messages.start'));
    }

    public function run()
    {
        $this->printEnterMessage();
        $this->crawl($this->nodes);
        $this->callExit();
    }

    public function crawl(array $nodes, $byPassThrow = false)
    {
        foreach ($nodes as $node) {
            if ($this->getBreak() || (!$byPassThrow && $this->getThrow())) {
                break;
            }
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

    public function addLevel($byPassThrow = false)
    {
        if ($byPassThrow || !$this->getThrow()) {
            $this->level++;
        }
    }

    public function removeLevel($byPassThrow = false)
    {
        if ($byPassThrow || !$this->getThrow()) {
            if ($this->level === 0) {
                throw new \Exception("Level cannot be reduced below zero.");
            }
            $this->level--;
        }
    }

    public function callBreak()
    {
        $this->break = true;
    }

    public function getBreak()
    {
        return $this->break;
    }

    public function removeBreak()
    {
        $this->break = false;
    }

    public function registerTry($try)
    {
        array_push($this->try, $try);
    }

    /**
     * 
     * @return Node\Stmt\TryCatch
     */
    public function unregisterTry()
    {
        return array_pop($this->try);
    }

    public function callThrow(\PhpParser\Node\Stmt\Throw_ $throw)
    {
        $this->throw = true;
        if (!empty($this->try)) {
            end($this->try)->resolveCatch($throw);
            return true;
        }
        return false;
    }

    public function getThrow()
    {
        return $this->throw;
    }

    public function removeThrow()
    {
        $this->throw = false;
    }

    public function callExit()
    {
        $this->level = 0;
        $this->printMessage(I18n::getInstance()->get('messages.end'));
        var_dump(Repository::getInstance()->getVariables());
        var_dump($this->nodes);
        if ($this->returnMessages) {
            return $this->messages;
        }
        exit;
    }

}
