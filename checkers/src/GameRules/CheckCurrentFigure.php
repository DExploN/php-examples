<?php


namespace src\GameRules;


use src\Exceptions\CheckersBaseException;
use src\Game;

class CheckCurrentFigure implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $currentFigure = $game->getTable()->findFigure($coords['x1'], $coords['y1']);

        if (!$currentFigure) {
            throw new CheckersBaseException('Фигура не выбрана');
        }

        if ($currentFigure->getPlayer() !== $game->getCurrentPlayer()) {
            throw new CheckersBaseException('Нельзя ходить чужой фигурой');
        }
        $game->executeRules($coords, $nextGameRule);
    }
}