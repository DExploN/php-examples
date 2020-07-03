<?php


namespace src\GameRules;


use src\Game;

class CreateKingRule implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $game->executeRules($coords, $nextGameRule);

        $currentFigure = $game->getTable()->findFigure($coords['x2'], $coords['y2']);
        $table = $game->getTable();

        if ($game->getCurrentPlayer() === Game::PLAYER_ONE && $currentFigure->getY() === 0) {
            $table->removeFigure($coords['x2'], $coords['y2']);
            $table->addFigure(
                $currentFigure = $table->getFigureFactory()->createKing(Game::PLAYER_ONE),
                $coords['x2'],
                $coords['y2']
            );
        }

        if ($game->getCurrentPlayer() === Game::PLAYER_TWO && $currentFigure->getY() === $table->getYMax()) {
            $table->removeFigure($coords['x2'], $coords['y2']);
            $table->addFigure(
                $currentFigure = $table->getFigureFactory()->createKing(Game::PLAYER_TWO),
                $coords['x2'],
                $coords['y2']
            );
        }
    }
}