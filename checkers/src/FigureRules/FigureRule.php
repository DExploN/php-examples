<?php

namespace src\FigureRules;

use src\Figure;
use src\Table;


interface FigureRule
{
    public function exec(Table $table, $x1, $y1, $x2, $y2): bool;

    public function findMoveTurns(Table $table, $x1, $y1): array;

    public function findAttackTurns(Table $table, $x1, $y1): array;

    public function isAttackRule(): bool;
}