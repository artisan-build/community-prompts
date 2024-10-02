<?php

namespace ArtisanBuild\CommunityPrompts;

use ArtisanBuild\CommunityPrompts\Output\AsyncConsoleOutput;
use Laravel\Prompts\Output\BufferedConsoleOutput;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\Support\Result;
use Override;
use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;
use Symfony\Component\Console\Output\OutputInterface;

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
    
    #[Override]
    public static function fakeKeyPresses(array $keys, callable $closure): void
    {
        static::$stdin ??= new ReadableResourceStream(STDIN);
        foreach ($keys as $key) {
            Loop::get()->futureTick(function () use ($key) {
                static::$stdin->emit('data', [$key]);
            });
        }

        self::setOutput(new BufferedConsoleOutput);
    }

    #[Override]
    public function runLoop(callable $callable): mixed
    {
        /**
         * @var  Result|null  $result
         */
        $result = null;

        static::$stdin ??= new ReadableResourceStream(STDIN);
        static::$stdin->on('data', function (string $key) use ($callable, &$result) {
            $result = $callable($key);

            if ($result instanceof Result) {
                Loop::stop();
            }
        });

        Loop::run();
        static::$stdin->removeAllListeners();

        if ($result === null) {
            throw new \RuntimeException('Prompt did not return a result.');
        }

        return $result->value;
    }

    /**
     * Set the output instance.
     */
    #[Override]
    public static function setOutput(OutputInterface $output): void
    {
        self::$output = $output;
    }

    /**
     * Get the current output instance.
     */
    #[Override]
    protected static function output(): OutputInterface
    {
        return self::$output ??= new AsyncConsoleOutput();
    }
}
