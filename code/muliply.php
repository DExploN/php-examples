<?php

/** Перемножение больших чисел */
function multiply(string $a, string $b): string
{
    $a = strrev($a);
    $b = strrev($b);

    $stolbik = [];

    for ($i = 0; $i < strlen($b); $i++) {
        $ostatok = 0;
        $stolbik [$i] = '';
        for ($j = 0; $j < strlen($a); $j++) {
            $charA = $a[$j];
            $charB = $b[$i];
            $res = ($charA * $charB + $ostatok) % 10;
            $ostatok = intdiv(($charA * $charB + $ostatok), 10);
            $stolbik [$i] = $stolbik [$i] . $res;
        }
        if ($ostatok) {
            $stolbik [$i] = $stolbik [$i] . $ostatok;
        }
        $stolbik [$i] = str_pad("", $i, "0") . $stolbik [$i];
    }
    $resultArray = [];
    $i = 0;
    $ostatok = 0;
    while (true) {
        $flag = false;
        $current = null;
        foreach ($stolbik as $el) {
            if (isset($el[$i])) {
                $current += (int)$el[$i];
                $flag = true;
            }
        }

        if (!$flag && !$ostatok) {
            break;
        }

        $resultArray[$i] = (string)(($current + $ostatok) % 10);
        $ostatok = intdiv($current + $ostatok, 10);
        $i++;
    }
    $result = ltrim(strrev(implode("", $resultArray)), "0");

    return $result === "" ? "0" : $result;
}

var_dump(multiply("3", "15"));