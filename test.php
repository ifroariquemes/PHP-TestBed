<?php

global $a, $b;
$a = 1;
const PhpTestBed = 'PhpTestBed';
$melhorLib = PhpTestBed == 'PhpTestBed';
$linguagem = 'PHP';

if ($melhorLib) {
    echo PhpTestBed . ' vai ajudar você compreender melhor seus algoritmos em ' . $linguagem . '!';
}