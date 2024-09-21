<?php

require_once __DIR__ . '\\Generate.php';

if (isset($argv[1]) && $argv[2] && $argv[3]) {

    $mode = $argv[1];
    $type = $argv[2];
    $filename = $argv[3];

    if ($mode === 'gen' or $mode === '-g' or $mode === 'generate') {
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
}

echo "invalid mono command\n";
