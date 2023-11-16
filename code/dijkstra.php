<?php

$graph = [
    '1' => ['2' => 7, '3' => 9, '6' => 14],
    '2' => ['1' => 7, '3' => 2, '4' => 15],
    '3' => ['1' => 9, '2' => 2, '4' => 11, '6' => 2],
    '4' => ['2' => 15, '3' => 11, '5' => 6],
    '5' => ['4' => 6, '6' => 9],
    '6' => ['1' => 14, '3' => 2, '5' => 9],
];
$start = 1;

// Validate graph

foreach ($graph as $keyOut => $row) {
    foreach ($row as $keyIn => $value) {
        if (empty($graph[$keyIn][$keyOut]) || $graph[$keyIn][$keyOut] !== $value) {
            die("Graph no valid $keyIn:$keyOut");
        }
    }
}
$copyGraph = $graph;

$queue = [$start => 0];
$result = [$start => ['range' => 0, 'path' => [$start]]];
$finished = [];

while (!empty($queue)) {
    $currentPoint = array_search(min($queue), $queue);
    $finished[] = $currentPoint;
    unset($queue[$currentPoint]);

    foreach ($graph[$currentPoint] as $point => $range) {
        if (!isset($result[$point]['range']) || $result[$currentPoint]['range'] + $range < $result[$point]['range']) {
            $result[$point]['range'] = $result[$currentPoint]['range'] + $range;
            $result[$point]['path'] = array_merge($result[$currentPoint]['path'], [$point]);
        }
        if (!in_array($point, $finished)) {
            $queue[$point] = $range;
        }
    }
}
var_dump($result);
