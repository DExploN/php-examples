<?php


namespace src;


use src\Exceptions\CheckersBaseException;
use src\Exceptions\FigureNotFoundException;

class Table
{
    private $xMax;
    private $yMax;
    private $matrix = [];
    private $printer;
    /**
     * @var FigureFactory
     */
    private FigureFactory $figureFactory;

    public function __construct($xMax, $yMax, TablePrinter $printer)
    {
        $this->xMax = $xMax;
        $this->yMax = $yMax;
        for ($i = 0; $i < $yMax; $i++) {
            for ($j = 0; $j < $xMax; $j++) {
                $this->matrix[$j][$i] = null;
            }
        }
        $printer->setTable($this);
        $this->printer = $printer;
        $this->figureFactory = new FigureFactory();
    }

    /**
     * @return mixed
     */
    public function getXMax()
    {
        return $this->xMax;
    }

    /**
     * @return mixed
     */
    public function getYMax()
    {
        return $this->yMax;
    }

    public function addFigure(Figure $figure, $x, $y)
    {
        if (!$this->verifyCoordinate($x, $y)) {
            echo "нельзя установить фигуру в $x, $y";
            return;
        }
        $this->matrix[$x][$y] = $figure;
        $figure->setX($x);
        $figure->setY($y);
        $figure->setTable($this);
    }

    public function findFigure($x, $y): ?Figure
    {
        return $this->matrix[$x][$y] ?? null;
    }

    public function getFigure($x, $y): Figure
    {
        if ($figure = $this->findFigure($x, $y)) {
            return $figure;
        }
        throw new CheckersBaseException("Фигура не найдена");
    }

    public function removeFigure($x, $y)
    {
        $figure = $this->findFigure($x, $y);
        if ($figure) {
            $figure->setX(null);
            $figure->setX(null);
        }
        $this->matrix[$x][$y] = null;
    }

    public function moveFigure($x1, $y1, $x2, $y2)
    {
        $figure = $this->findFigure($x1, $y1);
        if ($figure) {
            $this->matrix[$x2][$y2] = $this->matrix[$x1][$y1];
            $this->matrix[$x1][$y1] = null;
            $figure->setX($x2);
            $figure->setY($y2);
        }
    }

    public function print()
    {
        $this->printer->print();
    }

    /**
     * @return FigureFactory
     */
    public function getFigureFactory(): FigureFactory
    {
        return $this->figureFactory;
    }

    public function verifyCoordinate($x, $y): bool
    {
        if ($x >= 0 && $x < $this->xMax && $y >= 0 && $y < $this->yMax) {
            return true;
        } else {
            return false;
        }
    }

    /** @return Figure[] */
    public function findFigures()
    {
        return array_filter(
            array_reduce($this->matrix, 'array_merge', []),
            function ($item) {
                return $item !== null;
            }
        );
    }


}