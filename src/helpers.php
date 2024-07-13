<?php

namespace ArtisanBuild\CommunityPrompts;

use Closure;
use Illuminate\Support\Collection;

/**
 * Prompt the user to select an option from a scrollable tabbed list.
 *
 * @template TOption of array{id: int|string, tab: string, body: string}
 *
 * @param  array<int, TOption>|Collection<int, TOption>  $options
 * @param  int|Closure(Collection<int, TOption>): Collection<int, TOption>  $default  The default value for the prompt. If Closure, it is passed `$options` and should return a Collection containing only the desired record.
 */
function tabbedscrollableselect(string $label, array|Collection $options, int|Closure $default = 0, int $scroll = 14, int $max_width = 120, bool|string $required = true, mixed $validate = null, string $hint = ''): int|string|null
{
    return (new TabbedScrollableSelectPrompt(...func_get_args()))->prompt();
}
