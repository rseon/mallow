<?php

namespace Rseon\Mallow\Command;

abstract class AbstractCommand
{
    protected $options = [];
    protected $args = [];
    protected $help = '';
    protected $out = '';
    protected $command;

    abstract public function handle();

    /**
     * AbstractCommand constructor.
     * @param string $command
     * @param array $args
     */
    public function __construct(string $command, array $args)
    {
        $this->command = $command;
        $this->parseOpts($args);

        $help = $this->getOption('help', [
            'help' => 'h',
        ]);

        if($help) {
            echo $this->getHelp();
            exit(0);
        }
    }

    /**
     * @param string $k
     * @param array $aliases
     * @return mixed
     */
    public function getOption(string $k, array $aliases = [])
    {
        if(!$aliases) {
            $aliases = $this->aliases;
        }
        if(array_key_exists($k, $this->options)) {
            return $this->options[$k];
        }
        if(array_key_exists($k, $aliases) && array_key_exists($aliases[$k], $this->options)) {
            return $this->options[$aliases[$k]];
        }
    }

    /**
     * @param string $string
     * @return $this
     */
    public function print(string $string = '')
    {
        $this->out .= $string.PHP_EOL;
        return $this;
    }

    /**
     * @return string
     */
    public function out()
    {
        return $this->out;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param $argv
     *
     * @link https://github.com/asika32764/php-simple-console/blob/master/src/Console.php
     */
    protected function parseOpts($argv)
    {
        $key = null;

        $out = array();

        for ($i = 0, $j = count($argv); $i < $j; $i++) {
            $arg = $argv[$i];

            // --foo --bar=baz
            if (0 === strpos($arg, '--')) {
                $eqPos = strpos($arg, '=');

                // --foo
                if ($eqPos === false) {
                    $key = substr($arg, 2);

                    // --foo value
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                        $value = $argv[$i + 1];
                        $i++;
                    } else {
                        $value = isset($out[$key]) ? $out[$key] : true;
                    }

                    $out[$key] = $value;
                } else {
                    // --bar=baz
                    $key       = substr($arg, 2, $eqPos - 2);
                    $value     = substr($arg, $eqPos + 1);
                    $out[$key] = $value;
                }
            } elseif (0 === strpos($arg, '-')) {
                // -k=value -abc

                // -k=value
                if (isset($arg[2]) && $arg[2] === '=') {
                    $key       = $arg[1];
                    $value     = substr($arg, 3);
                    $out[$key] = $value;
                } else {
                    // -abc
                    $chars = str_split(substr($arg, 1));

                    foreach ($chars as $char) {
                        $key       = $char;
                        $out[$key] = isset($out[$key]) ? $out[$key] + 1 : 1;
                    }

                    // -a a-value
                    if (($i + 1 < $j) && ($argv[$i + 1][0] !== '-') && (count($chars) === 1)) {
                        $out[$key] = $argv[$i + 1];
                        $i++;
                    }
                }
            } else {
                // Plain-arg
                $this->args[] = $arg;
            }
        }

        $this->options = $out;
    }
}