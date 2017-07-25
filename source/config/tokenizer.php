<?php
/**
 * Tokenizer and Class locator component configurations.
 *
 * @see TokenizerConfig
 */
return [
    /*
     * Tokenizer will be performing class and invocation lookup in a following directories. Less
     * directories - faster Tokenizer will work.
     */
    'directories' => [
        app_path('/')
    ],
    /*
     * Such paths are excluded from tokenization. You can use format compatible with Symfony Finder.
     */
    'exclude'     => []
];