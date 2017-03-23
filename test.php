<?php

$lol = 'ab';
$a = [];
$b = array();
$c = [1, 2, 3];
$d = array(1, 2, 3);
$e = ['nome' => 'Natanael', 'cargo' => 'professor'];
$f = array('nome' => 'Natanael', 'cargo' => 'professor');
$o = $f;
$g = $c[0] + $d[0];
echo $c[1] . $e['nome'];

switch ($g) {
    case 1:
        echo '1';
        break;
    case 2:
    case 3:
        echo '2';
    case 'ola':
        echo 'tudo bem';
        break;
    default:
        echo 'nao deu em nada';
        break;
}
if($g) {
 echo 'hwlloo';   
}
//echo $c[0];