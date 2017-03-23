<?php
echo $a . $b;
$i = 0;
do {
    echo $i;
    for($j = 0; $j < 5; $j++) {
        echo $j;
        if($j == 2) {
            break;
        }
        echo $j;
    }
    if($i == 2) {
        break;
    }
    $i++;
} while($i < 5);