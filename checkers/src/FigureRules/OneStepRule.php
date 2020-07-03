<?php

namespace src\FigureRules;

use src\Table;

class OneStepRule implements FigureRule
{

    private $x;
    private $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function exec(Table $table, $x1, $y1, $x2, $y2): bool
    {
        $moves = $this->findMoveTurns($table, $x1, $y1);
        if (in_array(compact('x1', 'y1', 'x2', 'y2'), $moves)) {
            $table->moveFigure($x1, $y1, $x2, $y2);
            return true;
        }
        return false;
    }


    public function findMoveTurns(Table $table, $x1, $y1): array
    {
        if (!$table->findFigure($x1 + $this->x, $y1 + $this->y) && $table->verifyCoordinate(
                $x1 + $this->x,
                $y1 + $this->y
            )) {
            return [['x1' => $x1, 'y1' => $y1, 'x2' => ($x1 + $this->x), 'y2' => ($y1 + $this->y)]];
        }
        return [];
    }

    public function findAttackTurns(Table $table, $x1, $y1): array
    {
        return [];
    }

    public function isAttackRule(): bool
    {
        return false;
    }
}