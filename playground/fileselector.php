<?php

use function ArtisanBuild\CommunityPrompts\fileselector;

require __DIR__.'/../vendor/autoload.php';

$file2import = fileselector(
    label: 'Select a file to import.',
    placeholder: 'E.g. ./vendor/autoload.php',
    validate: fn (string $value) => match (true) {
        ! is_readable($value) => 'Cannot read the file.',
        default => null,
    },
    hint: 'Input the file path.',
    extensions: [
        '.json',
        '.php',
    ],
);

var_dump($file2import);

echo str_repeat(PHP_EOL, 1);
