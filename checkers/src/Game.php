<?php


namespace src;


use src\Exceptions\CheckersBaseException;

class Game
{
    /**
     * @var Table
     */
    private Table $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }


    public function run()
    {
        $this->table->print();
        $team = false;
        while (true) {
            $currentTurn = null;
            while (true) {
                try {
                    $figures = $this->table->findFigures();
                    $attackTurns = [];
                    $moveTurns = [];

                    foreach ($figures as $figure) {
                        if ($team === $figure->getTeam()) {
                            $attackTurns = array_merge($attackTurns, $figure->findAttackTurns());
                            $moveTurns = array_merge($moveTurns, $figure->findMoveTurns());
                        }
                    }

                    if (!$currentTurn && empty($attackTurns) && empty($moveTurns)) {
                        echo "Gamer " . (intval($team) + 1) . " проиграл" . PHP_EOL;
                        break 2;
                    }


                    $command = readline("Gamer " . (intval($team) + 1) . ":");
                    $command = strtoupper($command);
                    $coords = $this->getCoordinatesFromCommand($command);

                    if (!$this->table->verifyCoordinate($coords['x1'], $coords['y1']) ||
                        !$this->table->verifyCoordinate($coords['x2'], $coords['y2'])) {
                        throw new CheckersBaseException('Неверные координаты');
                    }

                    if ($currentTurn && $currentTurn !== [$coords['x1'], $coords['y1']]) {
                        throw new CheckersBaseException('Ходите той же фигурой');
                    }

                    $currentFigure = $this->table->getFigure($coords['x1'], $coords['y1']);


                    if (!$currentFigure) {
                        throw new CheckersBaseException('Фигура не выбрана');
                    }

                    if ($currentFigure->getTeam() !== $team) {
                        throw new CheckersBaseException('Нельзя ходить чужой фигурой');
                    }

                    if (!empty($attackTurns) && !in_array($coords, $attackTurns)) {
                        throw new CheckersBaseException('Необходимо бить');
                    }

                    $rule = $currentFigure->move($coords['x2'], $coords['y2']);


                    if ($team === false && $currentFigure->getY() === 0) {
                        $this->table->removeFigure($coords['x2'], $coords['y2']);
                        $this->table->addFigure(
                            $currentFigure = $this->table->getFigureFactory()->createKing($team),
                            $coords['x2'],
                            $coords['y2']
                        );
                    }

                    if ($team === true && $currentFigure->getY() === $this->table->getYMax()) {
                        $this->table->removeFigure($coords['x2'], $coords['y2']);
                        $this->table->addFigure(
                            $currentFigure = $this->table->getFigureFactory()->createKing($team),
                            $coords['x2'],
                            $coords['y2']
                        );
                    }

                    $this->table->print();

                    if ($currentFigure->findAttackTurns() && $rule->isAttackRule()) {
                        $currentTurn = [$currentFigure->getX(), $currentFigure->getY()];
                        continue;
                    }

                    $currentTurn = null;
                    $team = !$team;
                    continue 2;
                } catch (CheckersBaseException $e) {
                    echo $e->getMessage() . PHP_EOL;
                    continue;
                }
            }
        }
    }

    protected function getCoordinatesFromCommand($command)
    {
        $command = strtoupper($command);
        preg_match("/(?P<x1>[a-z])(?P<y1>[0-9])-(?P<x2>[a-z])(?P<y2>[0-9])/i", $command, $matches);
        $coords['x1'] = ord($matches['x1']) - 65;
        $coords['x2'] = ord($matches['x2']) - 65;
        $coords['y1'] = $this->table->getYMax() - $matches['y1'];
        $coords['y2'] = $this->table->getYMax() - $matches['y2'];
        return $coords;
    }
}