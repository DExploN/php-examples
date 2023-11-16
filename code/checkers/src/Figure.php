<?php


namespace src;


use src\Exceptions\CheckersBaseException;
use src\FigureRules\FigureRule;

class Figure
{
    private $unicodeSymbol;
    /** @var array | FigureRule[] */
    private $rules = [];
    /** @var int|null */
    private $x;
    /** @var int|null */
    private $y;
    /** @var Table|null */
    private ?Table $table;
    private string $player;


    public function __construct($unicodeSymbol, string $player)
    {
        $this->unicodeSymbol = $unicodeSymbol;
        $this->player = $player;
    }

    /**
     * @return mixed
     */
    public function getUnicodeSymbol()
    {
        return $this->unicodeSymbol;
    }

    public function addRule(FigureRule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * @return array | FigureRule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function move($x, $y): FigureRule
    {
        foreach ($this->rules as $rule) {
            if ($res = $rule->exec($this->getTable(), $this->getX(), $this->getY(), $x, $y)) {
                return $rule;
            }
        }
        throw new CheckersBaseException("Фигура не может сделать такой ход");
    }

    /**
     * @return int|null
     */
    public function getX(): ?int
    {
        return $this->x;
    }

    /**
     * @param int|null $x
     */
    public function setX(?int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int|null
     */
    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @param int|null $y
     */
    public function setY(?int $y): void
    {
        $this->y = $y;
    }

    /**
     * @return Table
     */
    public function getTable(): ?Table
    {
        return $this->table;
    }

    /**
     * @param Table $table
     */
    public function setTable(?Table $table): void
    {
        $this->table = $table;
    }


    public function getPlayer()
    {
        return $this->player;
    }

    public function findAttackTurns()
    {
        $attackMoves = [];
        foreach ($this->getRules() as $rule) {
            $attackMoves = array_merge(
                $attackMoves,
                $rule->findAttackTurns($this->getTable(), $this->getX(), $this->getY())
            );
        }
        return $attackMoves;
    }

    public function findMoveTurns()
    {
        $moveMoves = [];
        foreach ($this->getRules() as $rule) {
            $moveMoves = array_merge($moveMoves, $rule->findMoveTurns($this->getTable(), $this->getX(), $this->getY()));
        }
        return $moveMoves;
    }

}