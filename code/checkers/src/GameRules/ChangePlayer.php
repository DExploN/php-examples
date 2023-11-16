<?php


namespace src\GameRules;


use src\Exceptions\CheckersBaseException;
use src\Game;

class ChangePlayer implements GameRule
{
    private $currentFigure = null;

    public function execute(Game $game, array $coords, GameRule $nextGameRule)
    {
        if ($this->currentFigure && $this->currentFigure !== $game->getTable()->getFigure(
                $coords['x1'],
                $coords['y1']
            )) {
            throw new CheckersBaseException("Необходимо походить той же фигурой");
        }

        $game->executeRules($coords, $nextGameRule);

        $currentFigure = $game->getTable()->getFigure($coords['x2'], $coords['y2']);

        $history = $game->getHistory();
        $store = $history[array_key_last($history)];

        if ($currentFigure->findAttackTurns() && $store && $store['rule']->isAttack()) {
            $this->currentFigure = $currentFigure;
        } else {
            $this->currentFigure = null;
            $game->changeCurrentPlayer();
        }
    }
}