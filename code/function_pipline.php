<?php
function pipeline(...$args)
{
    $mid = array_shift($args);
    if ($mid) {
        return function (...$prop) use ($mid, $args) {
            return $mid(pipeline(...$args), $prop);
        };
    } else {
        return function () {
        };
    }
}

$f = function ($callable, $prop){
    return 'f'.$prop[0];
};

$mid = function ($callable, $prop){
    return 'm'.$prop[0]. $callable(...$prop);
};

$pipe = pipeline($mid, $mid, $f);
echo $pipe('_');
