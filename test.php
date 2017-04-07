<?php
global $a, $b;
$a = 1;
const PhpTestBed = 'PhpTestBed';
$melhorLib = PhpTestBed == 'PhpTestBed';
$linguagem = 'PHP';

if ($melhorLib) {
    echo PhpTestBed . ' vai ajudar você compreender melhor seus algoritmos em ' . $linguagem . '!';
}
echo 1;
try {
    echo 'entrou';
    throw new Exception('helo');
} catch (Exception $ex) {
    echo 'catch';
}