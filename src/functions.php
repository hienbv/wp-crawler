<?php

function print_out($str, $format)
{
    echo sprintf($format . PHP_EOL, $str);
}

function str_contains($str, array $arr)
{
    if (count($arr) === 0) {
        return true;
    }

    foreach ($arr as $a) {
        if (stripos($str, $a) !== false)
            return true;
    }
    return false;
}

function str_ignores($str, array $arr)
{
    if (count($arr) === 0) {
        return true;
    }

    foreach ($arr as $a) {
        if (stripos($str, $a) === false)
            return true;
    }
    return false;
}
