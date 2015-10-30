<?php
/**
 * Spiral tokenizer component configuration, includes only black and white listed directories to
 * be indexed.
 */
return [
    'directories' => [
        app_path('/')
    ],
    'exclude'     => [
        //No need to exclude anything
    ]
];
