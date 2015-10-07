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
        //No need to exclude anything, we are not under spiral which allows to index classes
        //in vendor folder
    ]
];