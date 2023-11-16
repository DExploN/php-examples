<?php


namespace src;


use phpDocumentor\Reflection\Types\Compound;
use src\Exceptions\CheckersBaseException;
use src\Exceptions\StopTurnException;
use src\FigureRules\FigureRule;
use src\GameRules\GameRule;
use src\GameRules\StopRule;

class Game
{
    /**
     * @var Table
     */
    private Table $table;

    /** @var GameRule[] */
    private $gameRules;
    private $runing = false;
    private $history = [];

    public const PLAYERS = [self::PLAYER_ONE, self::PLAYER_TWO];
    public const PLAYER_ONE = 'white';
    public const PLAYER_TWO = 'black';
    private $currentPlayer;

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->currentPlayer = self::PLAYERS[0];
    }

    public function addRule(GameRule $rule)
    {
        $this->gameRules[] = $rule;
        return $this;
    }

    public function executeRules($coordinates, GameRule $gameRule)
    {
        $index = array_search($gameRule, $this->gameRules);
        if ($index === false && !($gameRule instanceof $gameRule)) {
            throw new CheckersBaseException("Не найдено правило игры");
        }
        $nextRule = isset($this->gameRules[$index + 1]) ? $this->gameRules[$index + 1] : new StopRule();
        $gameRule->execute($this, $coordinates, $nextRule);
    }


    public function run()
    {
        $this->runing = true;
        $this->table->print();
        while ($this->runing) {
            try {
                $this->executeRules([], $this->gameRules[0]);
                $this->table->print();
            } catch (CheckersBaseException $exception) {
                echo $exception->getMessage() . PHP_EOL;
            } catch (StopTurnException $e) {
            }
        }
    }


    /**
     * @return mixed
     */
    public function getCurrentPlayer()
    {
        return $this->currentPlayer;
    }

    public function changeCurrentPlayer()
    {
        $this->currentPlayer = $this->currentPlayer === self::PLAYER_ONE ? self::PLAYER_TWO : self::PLAYER_ONE;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    public function stop()
    {
        $this->runing = false;
    }

    public function addHistory(array $coordinates, FigureRule $rule)
    {
        array_push($this->history, ['coordinates' => $coordinates, 'rule' => $rule]);
    }

    public function getHistory(): array
    {
        return $this->history;
    }
}