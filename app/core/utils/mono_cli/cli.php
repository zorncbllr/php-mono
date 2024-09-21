<?php

require_once __DIR__ . '\\Generate.php';

if (isset($argv[1]) && $argv[2]) {

    $type = $argv[1];
    $filename = $argv[2];

    if ($type === 'con' or $type === 'controller') {
        Generate::createNewController($filename);
    } elseif ($type === 'mod' or $type === 'model') {
        Generate::createNewModule($filename);
    } elseif ($type === 'vw' or $type === 'view') {
        Generate::createView($filename);
    } elseif ($type === 'comp' or $type === 'component') {
        Generate::createNewComponent($filename);
    }
}

echo "invalid mono command\n";
