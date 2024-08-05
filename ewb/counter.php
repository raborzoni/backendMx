<?php

function incrementCounter() {
    $counterFile = 'ewb/contador.txt';

    if (file_exists($counterFile)) {
        $currentValue = intval(file_get_contents($counterFile));
    } else {
        $currentValue = 0;
        file_put_contents($counterFile, $currentValue);
    }

    $currentValue++;
    file_put_contents($counterFile, $currentValue);

    return $currentValue;
}
