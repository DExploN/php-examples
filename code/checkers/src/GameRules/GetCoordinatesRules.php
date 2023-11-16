<?php


namespace src\GameRules;


use src\Exceptions\CheckersBaseException;
use src\Game;

class GetCoordinatesRules implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $command = readline("Gamer " . $game->getCurrentPlayer() . ":");
        $command = strtoupper($command);
        $command = strtoupper($command);

        preg_match("/(?P<x1>[a-z])(?P<y1>[0-9])-(?P<x2>[a-z])(?P<y2>[0-9])/i", $command, $matches);
        $coords['x1'] = ord($matches['x1']) - 65;
        $coords['x2'] = ord($matches['x2']) - 65;
        $coords['y1'] = $game->getTable()->getYMax() - $matches['y1'];
        $coords['y2'] = $game->getTable()->getYMax() - $matches['y2'];


        if (!$game->getTable()->verifyCoordinate($coords['x1'], $coords['y1']) ||
            !$game->getTable()->verifyCoordinate($coords['x2'], $coords['y2'])) {
            throw new CheckersBaseException('Неверные координаты');
        }

        $game->executeRules($coords, $nextGameRule);
    }
}