<?php

namespace ArtisanBuild\CommunityPrompts\Output;

use Laravel\Prompts\Output\ConsoleOutput;
use Override;
use React\Stream\WritableResourceStream;

class AsyncConsoleOutput extends ConsoleOutput
{
    protected WritableResourceStream $stdout;

    /**
     * Write to the output buffer.
     */
    #[Override]
    protected function doWrite(string $message, bool $newline): void
    {
        $this->stdout ??= new WritableResourceStream(STDOUT);
        $this->stdout->write($message);

        if ($newline) {
            $this->stdout->write(PHP_EOL);
        }
    }

    /**
     * Write output directly, bypassing newline capture.
     */
    #[Override]
    public function writeDirectly(string $message): void
    {
        $this->doWrite($message, false);
    }
}
