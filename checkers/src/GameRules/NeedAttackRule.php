<?php


namespace src\GameRules;


use src\Exceptions\CheckersBaseException;
use src\Game;

class NeedAttackRule implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $figures = $game->getTable()->findFigures();
        $attackTurns = [];

        foreach ($figures as $figure) {
            if ($game->getCurrentPlayer() === $figure->getPlayer()) {
                $attackTurns = array_merge($attackTurns, $figure->findAttackTurns());
            }
        }
        if (!empty($attackTurns) && !in_array($coords, $attackTurns)) {
            throw new CheckersBaseException('Необходимо бить');
        }

        $game->executeRules($coords, $nextGameRule);
    }
}