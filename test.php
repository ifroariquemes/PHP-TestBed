<?php

if (true) {
    try {
        for ($i = 0; $i < 5; $i++) {
            if ($i == 2) {
                throw new \PhpTestBed\PhpExcep('out');
            }
            echo $i;
            throw new Exception('lo');
        }
    } catch (PhpTestBed\PhpExcep $e) {
        echo 'phpexcep';
    } catch (Exception $e) {
        echo 'excep';
    } finally {
        echo 'final';
    }
}