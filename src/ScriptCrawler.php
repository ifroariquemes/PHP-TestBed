<?php

namespace PhpTestBed;

use PhpParser\ParserFactory;

/**
 * Main class that manages all the aplication. It simulates the execution of
 * a PHP script line-by-line and coordinates message flux that explains
 * what is going on in that statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.1.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class ScriptCrawler
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    /**
     * The script content.
     * @var string 
     */
    private $scriptContent;

    /**
     * The script relative/absolute path.
     * @var string
     */
    private $scriptPath;

    /**
     * Array of statements to be executed.
     * @var \PhpParser\Node[] 
     */
    private $nodes;

    /**
     * The current level of nesting while executing the script.
     * @var int
     */
    private $level;

    /**
     * If will use timestamp informartion on every generated message.
     * @var bool
     */
    private $useTimestamp;

    /**
     * If generated messsages will be prompted directy on screen or pushed
     * into a array and then returned when execution ends.
     * @var bool
     */
    private $returnMessages;

    /**
     * Stores the messages to be returned if $returnMessages is true.
     * @var string[] 
     */
    private $messages;

    /**
     * True if break is called inside a statement, stops the execution of that
     * statement.
     * @var bool
     */
    private $break;

    /**
     * Stores all try-catch-finally statements being executed to skip if
     * any error occours. It also start the recognition of the right catch
     * statement that is resposible for treat that specific exception type.
     * @var Node\Stmt\TryCatch[]
     */
    private $try;

    /**
     * True if a throw is called, stops the script execution if there is no 
     * catch statement to handle it.
     * @var bool
     */
    private $throw;

    /**
     * Instantiate new object.
     */
    public function __construct()
    {
        $this->level = 0;
        $this->messages = array();
        $this->try = array();
        $this->break = false;
        $this->throw = false;
    }

    /**
     * Starts the execution process
     * @param \PhpTestBed\ScriptCrawler\Options $options Execution options
     * @return mixed Array with generated messages if configured to
     * @throws \Exception If script path does not exists.
     */
    public function run(ScriptCrawler\Options $options): array
    {
        $this->scriptPath = $options->scriptPath;
        $this->useTimestamp = $options->useTimestamp;
        $this->returnMessages = $options->returnMessages;
        if (!\file_exists($this->scriptPath)) {
            throw new \Exception(I18n::getInstance()
                    ->get('exceptions.script-not-found'
                            , ['script' => $this->scriptPath]));
        }
        $this->scriptContent = file_get_contents($this->scriptPath);
        $this->parseScript();
        $this->printEnterMessage();
        $this->crawl($this->nodes);
        $this->callExit();
        return $this->messages;
    }

    /**
     * Load the statements nodes parsing the script content.
     */
    private function parseScript()
    {
        ini_set('xdebug.max_nesting_level', 3000);
        $parseFactory = new ParserFactory();
        $parser = $parseFactory->create(ParserFactory::PREFER_PHP7);
        $this->nodes = $parser->parse($this->scriptContent);
    }

    /**
     * Prints the start message.
     */
    private function printEnterMessage()
    {
        $this->printMessage(I18n::getInstance()->get('messages.start'));
    }

    /**
     * Iterates over given statements (nodes).
     * @param array $nodes Nodes with statements
     * @param boolean $byPassThrow Will execute even if throw was called
     */
    public function crawl(array $nodes, bool $byPassThrow = false)
    {
        foreach ($nodes as $node) {
            if ($this->getBreak() || (!$byPassThrow && $this->getThrow())) {
                break;
            }
            Node\NodeLoader::load($node);
            Repository::getInstance()->cleanUsed();
        }
    }

    /**
     * Prints a message into screen (if not returning messages) or push message
     * into messages array (if returning messages).
     * @param string $message The message to be print
     */
    public function printMessage(string $message)
    {
        if (!empty($message)) {
            (!$this->returnMessages) ?
                            $this->echoMessage($message) :
                            $this->addMessage($message);
        }
    }

    /**
     * Returns current date and time with microseconds information.
     * @return \DateTime
     */
    private function getTimestamp(): \DateTime
    {
        $millis = microtime(true);
        $micro = sprintf("%06d", ($millis - floor($millis)) * 1E6);
        return new \DateTime(date('Y-m-d H:i:s.' . $micro, $millis));
    }

    /**
     * Prints a message into screen.
     * @param string $message The message to be print
     */
    private function echoMessage(string $message)
    {
        $spaces = str_repeat('<span class="testbed-level"></span>', $this->level);
        echo ($this->useTimestamp) ?
                sprintf('<span class="testbed-timestamp">[%s]</span> <span class="testbed-message">%s%s</span><br>'
                        , $this->getTimestamp()->format(
                                I18n::getInstance()->get('config.timestamp')
                        ), $spaces, $message) :
                sprintf('<span class="testbed-message">%s%s</span><br>'
                        , $spaces, $message);
    }

    /**
     * Adds a message to the returning messages pool.
     * @param string $message The message to be add
     */
    private function addMessage(string $message)
    {
        $spaces = str_repeat('\t', $this->level);
        array_push($this->messages, ($this->useTimestamp) ?
                        sprintf('[%s] %s%s'
                                , $this->getTimestamp()->format(
                                        I18n::getInstance()->get('config.timestamp')
                                ), $spaces, preg_replace('/<\\/?span(\\s+.*?>|>)/', '', $message)) :
                        sprintf('%s%s'
                                , $spaces, preg_replace('/<\\/?span(\\s+.*?>|>)/', '', $message)));
    }

    /**
     * Returns the current nesting level.
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Increases a nesting level.
     * @param bool $byPassThrow Will add a nesting level even if throw was called
     */
    public function addLevel(bool $byPassThrow = false)
    {
        if ($byPassThrow || !$this->getThrow()) {
            $this->level++;
        }
    }

    /**
     * Decreases a nesting level.
     * @param bool $byPassThrow Will remove a nesting level even if throw was called
     * @throws \Exception If tries to decrease below level 0
     */
    public function removeLevel(bool $byPassThrow = false)
    {
        if ($byPassThrow || !$this->getThrow()) {
            if ($this->level === 0) {
                throw new \Exception("Level cannot be reduced below zero.");
            }
            $this->level--;
        }
    }

    /**
     * When a break statement is executed then ScriptCrawler should skip
     * that crawl chain.
     */
    public function callBreak()
    {
        $this->break = true;
    }

    /**
     * Returns the break condition, if it was called or not during current
     * crawl chain.
     * @return bool
     */
    public function getBreak(): bool
    {
        return $this->break;
    }

    /**
     * Remove the break condition, so a new crawl chain can be executed
     */
    public function removeBreak()
    {
        $this->break = false;
    }

    /**
     * Register a try-catch-finally statement, so if a throw is called
     * then it will be used to find which catch has the aproprieated
     * condition to treat that exception.
     * @param \PhpTestBed\Node\Stmt\TryCatch $try The try-catch-finally statement
     */
    public function registerTry(Node\Stmt\TryCatch $try)
    {
        array_push($this->try, $try);
    }

    /**
     * Removes a try-catch-finally statement from this try pool. It happens
     * when a try is successfully executed with no erros or throws call.
     * @return Node\Stmt\TryCatch The unregistered try-catch-finally statement
     */
    public function unregisterTry(): Node\Stmt\TryCatch
    {
        return array_pop($this->try);
    }

    /**
     * When a throw statement is found on script, then this condition is called.
     * If there that throw is not within a try, then the script finishes.
     * @param \PhpParser\Node\Stmt\Throw_ $throw The throw statement
     * @return bool True if throw is within a try statement, false if not
     */
    public function callThrow(\PhpParser\Node\Stmt\Throw_ $throw): bool
    {
        $this->throw = true;
        if (!empty($this->try)) {
            end($this->try)->resolveCatch($throw);
            return true;
        }
        return false;
    }

    /**
     * Returns the current status of script throw call
     * @return bool
     */
    public function getThrow(): bool
    {
        return $this->throw;
    }

    /**
     * Removes the condition of throw call (interrupts script execution)
     */
    public function removeThrow()
    {
        $this->throw = false;
    }

    /**
     * Reset all conditions and prints the finish message
     */
    public function callExit()
    {
        $this->level = 0;
        $this->printMessage(I18n::getInstance()->get('messages.end'));
    }

    /**
     * Returns if this class was configured to return messages instead of
     * showing them at screen.
     * @return bool
     */
    public function getReturnMessage(): bool
    {
        return $this->returnMessages;
    }

}
