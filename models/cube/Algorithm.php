<?php

namespace app\models\cube;

use yii\base\Model;

class Algorithm extends Model {

    const MOVE_PATTERN = '/^(?:([2-9]|[1-9]\d+)?([URFDLB])w|([URFDLBEMSxyzurfdlb]))([2\']|2\')?$/';

    private $moves;

    public function __construct($algo) {
        if ($algo instanceof Algorithm) {
            $this->moves = $algo->moves;
            return;
        }
        $this->moves = self::filter($algo);
    }

    private static function filter($algo) {
        $algo = trim($algo);
        $moves = preg_split('/\s+/', $algo);
        $tmp = [];
        foreach ($moves as $move) {
            if (preg_match(self::MOVE_PATTERN, $move, $matches) == 0) {
                continue;
            }
            $tmp[] = $matches[0];
        }
        return $tmp;
    }

    public function applyTo(CubeNNN $cube) {
        foreach ($this->moves as $move) {
            preg_match(self::MOVE_PATTERN, $move, $matches);
            $amount = empty($matches[4]) ? 1 : strpos("2'", $matches[4]) + 2;
            if (!empty($matches[2])) {
                $end = empty($matches[1]) ? 2 : intval($matches[1]);
                $func = 'move' . $matches[2];
                $cube->$func($amount, $end);
            } else {
                $func = 'move' . strtoupper($matches[3]);
                switch ($matches[3]) {
                    case 'U':
                    case 'R':
                    case 'F':
                    case 'D':
                    case 'L':
                    case 'B':
                    case 'E':
                    case 'M':
                    case 'S':
                    case 'x':
                    case 'y':
                    case 'z':
                        $cube->$func($amount);
                        break;
                    case 'u':
                    case 'r':
                    case 'f':
                    case 'd':
                    case 'l':
                    case 'b':
                        $cube->$func($amount, 2, 2);
                        break;
                }
            }
        }
    }


}