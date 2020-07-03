<?php

namespace src\FigureRules;

use src\Figure;
use src\Table;

class ManyStepAttackRule implements FigureRule
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
            $table->moveFigure($x1, $y1, $x2, $y2);
            for ($i = 1; $x1 + $this->x * $i != $x2; $i++) {
                $table->removeFigure($x1 + $this->x * $i, $y1 + $this->y * $i);
            }
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
        $res = $this->findAttackTurnsOneLevel($table, $x1, $y1);
        $enemy = $res['enemy'];
        $turns = $res['turns'];

        $filteredTurns = [];

        if ($turns) {
            $currentFigure = $table->getFigure($x1, $y1);
            $enemy = $table->getFigure($enemy->getX(), $enemy->getY());

            $cloneCurrent = clone $currentFigure;
            $cloneEnemy = clone $enemy;

            $table->removeFigure($currentFigure->getX(), $currentFigure->getY());
            $table->removeFigure($enemy->getX(), $enemy->getY());

            foreach ($turns as $coords) {
                $table->addFigure($currentFigure, $coords['x2'], $coords['y2']);
                if ($currentFigure->findAttackTurns()) {
                    $filteredTurns[] = $coords;
                }
                $table->removeFigure($coords['x2'], $coords['y2']);
            }

            $table->addFigure($currentFigure, $cloneCurrent->getX(), $cloneCurrent->getY());
            $table->addFigure($enemy, $cloneEnemy->getX(), $cloneEnemy->getY());
            unset($cloneCurrent, $cloneEnemy);
        }
        return count($filteredTurns) ? $filteredTurns : $turns;
    }

    public function findAttackTurnsOneLevel(Table $table, $x1, $y1): array
    {
        $figure = $table->getFigure($x1, $y1);


        $result = ['turns' => [], 'enemy' => null];
        $i = 0;
        while (true) {
            $i++;
            $x = $x1 + $this->x * $i;
            $y = $y1 + $this->y * $i;

            if (!$table->verifyCoordinate($x, $y)) {
                break;
            }

            $target = $table->findFigure($x, $y);
            if (!$target) {
                continue;
            }
            $enemy = $target && $target->getPlayer() !== $figure->getPlayer();
            if (!$enemy) {
                break;
            }
            $result['enemy'] = $target;
            while (true) {
                $i++;
                $x = $x1 + $this->x * $i;
                $y = $y1 + $this->y * $i;

                if (!$table->verifyCoordinate($x, $y)) {
                    break 2;
                }

                if ($table->findFigure($x, $y)) {
                    break 2;
                }

                $result['turns'][] = ['x1' => $x1, 'y1' => $y1, 'x2' => $x, 'y2' => $y];
            }
        }

        return $result;
    }

    public function isAttack(): bool
    {
        return true;
    }


}