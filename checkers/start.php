<?php

use src\Figure;
use src\Game;
use src\GameRules\ChangePlayer;
use src\GameRules\CheckCurrentFigure;
use src\GameRules\CreateKingRule;
use src\GameRules\EndGameRule;
use src\GameRules\GetCoordinatesRules;
use src\GameRules\MoveFigure;
use src\GameRules\NeedAttackRule;
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

$table->addFigure($table->getFigureFactory()->createChecker(Game::PLAYER_TWO), 4, 1);
$table->addFigure($table->getFigureFactory()->createChecker(Game::PLAYER_TWO), 4, 3);

$table->addFigure($table->getFigureFactory()->createKing(Game::PLAYER_ONE), 2, 5);


$game->addRule(new EndGameRule());
$game->addRule(new GetCoordinatesRules());
$game->addRule(new CheckCurrentFigure());
$game->addRule(new ChangePlayer());
$game->addRule(new CreateKingRule());
$game->addRule(new NeedAttackRule());
$game->addRule(new MoveFigure());

$game->run();


