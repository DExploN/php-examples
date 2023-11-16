<?php


namespace src\GameRules;


use src\Exceptions\CheckersBaseException;
use src\Exceptions\StopTurnException;
use src\Game;

class EndGameRule implements GameRule
{

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        $figures = $game->getTable()->findFigures();
        $attackTurns = [];
        $moveTurns = [];

        foreach ($figures as $figure) {
            if ($game->getCurrentPlayer() === $figure->getPlayer()) {
                $attackTurns = array_merge($attackTurns, $figure->findAttackTurns());
                $moveTurns = array_merge($moveTurns, $figure->findMoveTurns());
            }
        }
        if (empty($attackTurns) && empty($moveTurns)) {
            $game->stop();
            echo $game->getCurrentPlayer() . " проиграл" . PHP_EOL;
            throw new StopTurnException();
        }
        $game->executeRules($coords, $nextGameRule);
    }
}