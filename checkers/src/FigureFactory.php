<?php


namespace src;


use src\FigureRules\ManyStepAttackRule;
use src\FigureRules\ManyStepRule;
use src\FigureRules\OneStepAttackRule;
use src\FigureRules\OneStepRule;

class FigureFactory
{
    public function createChecker(bool $team): Figure
    {
        $code = $team ? "♙" : "♟";
        $checker = new Figure($code, $team);
        if ($team) {
            $checker->addRule(new OneStepRule(-1, 1));
            $checker->addRule(new OneStepRule(1, 1));
        } else {
            $checker->addRule(new OneStepRule(1, -1));
            $checker->addRule(new OneStepRule(-1, -1));
        }
        $checker->addRule(new OneStepAttackRule(-1, 1));
        $checker->addRule(new OneStepAttackRule(1, 1));
        $checker->addRule(new OneStepAttackRule(1, -1));
        $checker->addRule(new OneStepAttackRule(-1, -1));
        return $checker;
    }

    public function createKing(bool $team): Figure
    {
        $code = $team ? "♔" : "♚";
        $checker = new Figure($code, $team);
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