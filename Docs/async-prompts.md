# Asynchronous Prompts

In a standard Laravel Prompt, the looping mechanism blocks the main thread while waiting for keypresses. This limits our ability to trigger renders using an `event` based approach.

By **overwriting** these looping mechanisms in `ArtisanBuild/CommunityPrompts/AsyncPrompt` using a [ReactPHP](https://reactphp.org/) event loop. This unlocks the ability for us to read the terminal, write to the terminal, dispatch http requests, etc, in a non-blocking way. By calling the `render()` method inside `callbacks` we can now do things like debounce http requests to search endpoints, output streamed http responses to the terminal one chunk at a time without blocking the user from entering new text, or even listening for real-time push notifications from a websocket.
