<?php


namespace src;


class TablePrinter
{
    /**
     * @var Table
     */
    private Table $table;

    public function setTable(Table $table)
    {
        $this->table = $table;
    }

    public function print()
    {
        $xMax = $this->table->getXMax();
        $yMax = $this->table->getYMax();

        $this->printAlfaBet($xMax);

        echo '  ';
        for ($x = 1; $x <= $xMax; $x++) {
            echo "____";
        }
        echo '_' . PHP_EOL;

        for ($y = 0; $y < $yMax; $y++) {
            echo ($yMax - $y) . ' ';
            for ($x = 0; $x < $xMax; $x++) {
                $char = ($fig = $this->table->findFigure($x, $y)) ? $fig->getUnicodeSymbol() : " ";
                echo "| $char ";
                if ($x === $xMax - 1) {
                    echo "| " . ($yMax - $y);
                }
            }
            echo PHP_EOL;
            echo '  ';
            for ($x = 0; $x < $xMax; $x++) {
                echo "|___";
                if ($x === $xMax - 1) {
                    echo "|";
                }
            }
            echo PHP_EOL;
        }
        $this->printAlfaBet($xMax);
    }

    private function printAlfaBet($xMax)
    {
        echo '  ';
        for ($x = 0; $x < $xMax; $x++) {
            echo '  ' . chr(65 + $x) . ' ';
        }
        echo PHP_EOL;
    }
}