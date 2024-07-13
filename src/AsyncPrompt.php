<?php

namespace Laravel\Prompts;

use Closure;
use Laravel\Prompts\Output\AsyncConsoleOutput;
use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class AsyncPrompt extends Prompt
{
    /**
     * The output instance.
     * 
     * We redefine the output to prevent the parent class from
     * forcing a non-async console output on this instance.
     */
    protected static OutputInterface $output;

    protected static ReadableResourceStream $stdin;
    
    public static function fakeKeyPresses(array $keys, Closure $closure): void
    {
        static::$stdin ??= new ReadableResourceStream(STDIN);
        foreach ($keys as $key) {
            Loop::get()->futureTick(function () use ($key) {
                static::$stdin->emit('data', [$key]);
            });
        }
    }

    public function runLoop(callable $callable): mixed
    {
        $result = null;

        static::$stdin ??= new ReadableResourceStream(STDIN);
        static::$stdin->on('data', function (string $key) use ($callable, &$result) {
            $result = $callable($key);

            if (! $this->is_nothing($result)) {
                Loop::stop();
            }
        });

        Loop::run();
        static::$stdin->removeAllListeners();

        return $result;
    }

    /**
     * Get the current output instance.
     */
    protected static function output(): OutputInterface
    {
        return static::$output ??= new AsyncConsoleOutput();
    }
}
