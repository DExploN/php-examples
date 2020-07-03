<?php


namespace src\GameRules;


use src\Game;

class MoveFigure implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $figure = $game->getTable()->findFigure($coords['x1'], $coords['y1']);
        $rule = $figure->move($coords['x2'], $coords['y2']);
        $game->addHistory($coords, $rule);
        $game->executeRules($coords, $nextGameRule);
    }
}