<?php

global $a1, $b1;

const FILE = 'test', TYPE = 'php';

$a = 10;

$b1 = $a1 + $a;

echo "Ola $a Jow " . FILE . TYPE;

$d = $a << 2;

echo "Teste $a $d";
echo $a + $d + 1 * 5 / 2;
$c = $a + 5 + 2;
$b = $a > 5;
$e = 255;
$f = $d ^ $e;

$ola = $a;
$test = 15;
$theInc = 1;
echo $a;
while ($test >= 10) {
    echo "olá";
    echo --$test . 'aho';
    $inc = $theInc--;
    echo $inc--;
}

for ($i = 15; $i > $a; $i--) {
    $b = $i;
    //   echo $b + 1;
}

do {
    $ts = $ts + 1;
    if ($ts == 5)
        echo 'vai acabai...';
    else
        echo 'ainda nao...';
} while ($ts < 5);

if ($ts == 1) {
    echo "jamais aqui";
} elseif ($ts == 3) {
    echo "nem aqui";
} else {
    echo "certamente";
}