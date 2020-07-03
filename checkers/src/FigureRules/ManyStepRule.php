<?php

namespace src\FigureRules;

use src\Table;

class ManyStepRule implements FigureRule
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
        $result = [];
        $i = 1;
        while (true) {
            $x = $x1 + $this->x * $i;
            $y = $y1 + $this->y * $i;
            if (!$table->findFigure($x, $y) && $table->verifyCoordinate($x, $y1)) {
                $result[] = ['x1' => $x1, 'y1' => $y1, 'x2' => $x, 'y2' => $y];
            } else {
                break;
            }
            $i++;
        }


        return $result;
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