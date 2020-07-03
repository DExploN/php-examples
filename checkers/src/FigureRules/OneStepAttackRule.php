<?php

namespace src\FigureRules;

use src\Figure;
use src\Table;

class OneStepAttackRule implements FigureRule
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
        if (in_array(compact('x1', 'y1', 'x2', 'y2'), $this->findAttackTurns($table, $x1, $y1))) {
            $table->removeFigure($x1 + $this->x, $y1 + $this->y);
            $table->moveFigure($x1, $y1, $x2, $y2);
            return true;
        }
        return false;
    }

    public function findMoveTurns(Table $table, $x1, $y1): array
    {
        return [];
    }

    public function findAttackTurns(Table $table, $x1, $y1): array
    {
        $figure = $table->getFigure($x1, $y1);

        $target = $table->findFigure($x1 + $this->x, $y1 + $this->y);
        $enemy = $target && $target->getTeam() !== $figure->getTeam();
        $canAttack = !$table->findFigure($x1 + $this->x * 2, $y1 + $this->y * 2) && $table->verifyCoordinate(
                $x1 + $this->x * 2,
                $y1 + $this->y * 2
            );

        if ($enemy && $canAttack) {
            return [['x1' => $x1, 'y1' => $y1, 'x2' => ($x1 + $this->x * 2), 'y2' => ($y1 + $this->y * 2)]];
        }
        return [];
    }

    public function isAttackRule(): bool
    {
        return true;
    }
}