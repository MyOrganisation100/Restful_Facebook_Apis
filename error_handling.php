<?php
ini_set("display_errors", 1);

function customErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "Error: [$errno] $errstr - $errfile : $errline\n\n";
    echo "<hr>";
}

set_error_handler("customErrorHandler");
