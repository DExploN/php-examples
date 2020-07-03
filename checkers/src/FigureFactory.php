<?php


namespace src;


use src\FigureRules\ManyStepAttackRule;
use src\FigureRules\ManyStepRule;
use src\FigureRules\OneStepAttackRule;
use src\FigureRules\OneStepRule;

class FigureFactory
{
    public function createChecker(string $player): Figure
    {
        $code = $player === Game::PLAYER_TWO ? "♙" : "♟";
        $checker = new Figure($code, $player);
        if ($player === Game::PLAYER_ONE) {
            $checker->addRule(new OneStepRule(-1, -1));
            $checker->addRule(new OneStepRule(1, -1));
        } else {
            $checker->addRule(new OneStepRule(1, 1));
            $checker->addRule(new OneStepRule(-1, 1));
        }
        $checker->addRule(new OneStepAttackRule(-1, 1));
        $checker->addRule(new OneStepAttackRule(1, 1));
        $checker->addRule(new OneStepAttackRule(1, -1));
        $checker->addRule(new OneStepAttackRule(-1, -1));
        return $checker;
    }

    public function createQueen(string $player): Figure
    {
        $code = $player === Game::PLAYER_TWO ? "♔" : "♚";
        $checker = new Figure($code, $player);
        $checker->addRule(new ManyStepRule(-1, 1));
        $checker->addRule(new ManyStepRule(1, 1));
        $checker->addRule(new ManyStepRule(1, -1));
        $checker->addRule(new ManyStepRule(-1, -1));
        $checker->addRule(new ManyStepAttackRule(-1, 1));
        $checker->addRule(new ManyStepAttackRule(1, 1));
        $checker->addRule(new ManyStepAttackRule(1, -1));
        $checker->addRule(new ManyStepAttackRule(-1, -1));
        return $checker;
    }

}