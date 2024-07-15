# Fileselector

The `fileselector` function provides assistance in entering file paths `quickly` and `accurately` in interactive mode.

https://github.com/laravel/prompts/assets/19181121/03c6f46d-a19d-4de0-af71-66b65c33e499

## Basic Usage

The `fileselector` function can be used to provide auto-completion for possible choices.

The user can still provide any answer, regardless of the auto-completion hints:

```php
use function ArtisanBuild\CommunityPrompts\fileselector;

$file2import = fileselector('Select a file to import.');
```

This function lists the entries in the directory on the local file system that matches the input, as the options for suggest.

The user can automatically complete the input by pressing `TAB` on the selected option, then continue to input.
The user can finish input by pressing `Enter`.

You may also include placeholder text, a default value, and an informational hint:

```php
$file2import = fileselector(
    label: 'Select a file to import.',
    placeholder: 'E.g. ./vendor/autoload.php',
    default: '',
    hint: 'Input the file path.'
);
```

## Required Values

If you require a value to be entered, you may pass the `required` argument:

```php
$file2import = fileselector(
    label: 'Select a file to import.',
    required: true
);
```

If you would like to customize the validation message, you may also pass a string:

```php
$file2import = fileselector(
    label: 'Select a file to import.',
    required: 'File path is required.'
);
```

## Additional Validation

If you would like to perform additional validation logic, you may pass a closure to the `validate` argument:

```php
$file2import = fileselector(
    label: 'Select a file to import.',
    validate: fn (string $value) => match (true) {
        !is_readable($value) => 'Cannot read the file.',
        default => null
    }
);
```

The closure will receive the value that has been entered and may return an error message, or `null` if the validation passes.

## Filtering by File Extensions

Finally, you can filter the options by passing the lists of file extensions to the parameter `extensions`.

```php
$file2import = fileselector(
    label: 'Select a file to import.',
    extensions: [
        '.json',
        '.php',
    ],
);
```

If the parameter `extensions` is specified, the path and directories whose path ends match one of the extensions array elements are returned as options.
