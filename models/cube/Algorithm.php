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

    public function getReverse($prefix = false) {
        $REVERSE_MAP = [
            "'" => '',
            '2' => "2'",
            "2'" => '2',
        ];
        $rev = [];
        $prefixes = [];
        foreach ($this->moves as $move) {
            $tmp = '';
            preg_match(self::MOVE_PATTERN, $move, $matches);
            if (!empty($matches[2])) {
                $end = empty($matches[1]) ? 2 : intval($matches[1]);
                $tmp .= empty($matches[1]) ? '' : $matches[1];
                $tmp .= $matches[2] . 'w';
            } else {
                $tmp .= $matches[3];
            }
            $tmp .= empty($matches[4]) ? "'" : $REVERSE_MAP[$matches[4]];
            if ($prefix) {
                switch ($tmp) {
                    case "Rw":
                    case "M'":
                    case "Lw'":
                    case "x":
                        $prefixes[] = "x'";
                        break;
                    case "Rw'":
                    case "M":
                    case "Lw":
                    case "x'":
                        $prefixes[] = "x";
                        break;
                    case "Rw2":
                    case "Rw2'":
                    case "M2":
                    case "M2'":
                    case "Lw2":
                    case "Lw2'":
                    case "x2":
                    case "x2'":
                        $prefixes[] = "x2";
                        break;
                    case "Uw":
                    case "E'":
                    case "Dw'":
                    case "y":
                        $prefixes[] = "y'";
                        break;
                    case "Uw'":
                    case "E":
                    case "Dw":
                    case "y'":
                        $prefixes[] = "y";
                        break;
                    case "Uw2":
                    case "Uw2'":
                    case "E2":
                    case "E2'":
                    case "Dw2":
                    case "Dw2'":
                    case "y2":
                    case "y2'":
                        $prefixes[] = "y2";
                        break;
                    case "Fw":
                    case "S":
                    case "Bw'":
                    case "z":
                        $prefixes[] = "z'";
                        break;
                    case "Fw'":
                    case "S'":
                    case "Bw":
                    case "z'":
                        $prefixes[] = "z";
                        break;
                    case "Fw2":
                    case "Fw2'":
                    case "S2":
                    case "S2'":
                    case "Bw2":
                    case "Bw2'":
                    case "z2":
                    case "z2'":
                        $prefixes[] = "z2";
                        break;
                }
            }
            array_unshift($rev, $tmp);
        }
        return new Algorithm(implode(' ', array_merge($prefixes, $rev)));
    }

    public function __toString() {
        return implode(' ', $this->moves);
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
