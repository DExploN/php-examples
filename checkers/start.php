<?php

use src\Figure;
use src\Game;
use src\Table;
use src\TablePrinter;

require __DIR__ . "/vendor/autoload.php";

$size = 8;

$table = new Table($size, $size, new TablePrinter());
$game = new Game($table);


//for($i=0; $i< 3; $i++){
//    for($j=0; $j< 8; $j+=2) {
//        $table->addFigure($table->getFigureFactory()->createChecker(true),$j+(($i+1)%2),$i);
//    }
//}
//
//
//for($i=$size-3; $i< $size; $i++){
//    for($j=0; $j< 8; $j+=2) {
//        $table->addFigure($table->getFigureFactory()->createChecker(false),$j+(($i+1)%2),$i);
//    }
//}

$table->addFigure($table->getFigureFactory()->createChecker(true), 7, 0);

$table->addFigure($table->getFigureFactory()->createChecker(false), 4, 1);


$game->run();


