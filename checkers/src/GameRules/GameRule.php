<?php


namespace src\GameRules;


use src\Game;

interface GameRule
{
    public function execute(Game $game, array $coords, GameRule $nextGameRule);
}