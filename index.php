<?php
function countOffset($points)
{
    $maxX = null;
    $maxY = null;
    $minX = null;
    $minY = null;
    foreach ($points as $point) {
        if (is_null($maxX) || $maxX < $point[0]) 
            $maxX = $point[0];
        if (is_null($maxY) || $maxY < $point[1])
            $maxY = $point[1];
        if (is_null($minX) || $minX > $point[0])
            $minX = $point[0];
        if (is_null($minY) || $minY > $point[1])
            $minY = $point[1];
    }
    return [
        $maxX, $maxY, $minX, $minY
    ];
}
function addVelocity($points)
{
    foreach ($points as &$point) {
        $point[0] += $point[2];
        $point[1] += $point[3];
    }
    return $points;
}
function draw($points, $boundaries)
{
    $canvas = [];
    foreach($points as $point) {
        $canvas[$point[1] + abs($boundaries[3])][$point[0] + abs($boundaries[2])] = '<b style="color:red;">#</b>';
    }
    for($i = 0; $i < $boundaries[1] + abs($boundaries[3]) + 1; $i++) {
        $tmp = '';
        for ($j = 0; $j < $boundaries[0] + abs($boundaries[2]) + 1; $j++) {
            if (is_null($canvas[$i][$j])) {
                $canvas[$i][$j] = '<b style="color:white;">#</b>';
            }
            $tmp .= $canvas[$i][$j] . ' ';
        }
        echo $tmp. '</br>';
    }
}
$lines = file(__DIR__ . '/input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$points = array_map(function($line) {
    $matches = [];
    preg_match(
        '/position=<(\s[0-9]+|[-][0-9]+|[0-9]+),\s(\s[0-9]+|[-+][0-9]+|[0-9]+)>\svelocity=<(\s[0-9]+|[-+][0-9]+|[0-9]+),\s(\s[0-9]+|[-+][0-9]+|[0-9]+)>/',
        $line, 
        $matches
    );
    return [
        trim($matches[1]),
        trim($matches[2]),
        trim($matches[3]),
        trim($matches[4])
    ];
}, $lines);
for ($z = 0; $z < 11000; $z++) {
    if ($z != 0)
        $points = addVelocity($points);
    $boundaries = countOffset($points);    
    if($z === 10558)
        draw($points, $boundaries);
}