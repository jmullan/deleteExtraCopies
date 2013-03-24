#!/usr/bin/php
<?php
require(__DIR__ . '/deleteExtraCopies.class.php');

array_shift($argv);
if (empty($argv)) {
    echo 'You must specify files or directories to search and fix';
    exit(1);
}


$paths = array();
foreach ($argv as $arg) {
    if (file_exists($arg) && (is_dir($arg) || preg_match('/.mp3$/', $arg))) {
        $paths[] = $arg;
    } else {
        echo 'Problem with path: ' . $arg;
    }
}
$doer = new deleteExtraCopies();
foreach ($paths as $path) {
    $doer->doIt($path);
}