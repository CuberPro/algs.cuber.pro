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
                $moveMap = [
                    "x'" => ["Rw", "M'", "Lw'", "x"],
                    "x" => ["Rw'", "M", "Lw", "x'"],
                    "x2" => [
                        "Rw2", "Rw2'", "M2", "M2'", "Lw2", "Lw2'", "x2", "x2'"
                    ],
                    "y'" => ["Uw", "E'", "Dw'", "y"],
                    "y" => ["Uw'", "E", "Dw", "y'"],
                    "y2" => [
                        "Uw2", "Uw2'", "E2", "E2'", "Dw2", "Dw2'", "y2", "y2'"
                    ],
                    "z'" => ["Fw", "S", "Bw'", "z"],
                    "z" => ["Fw'", "S'", "Bw", "z'"],
                    "z2" => [
                        "Fw2", "Fw2'", "S2", "S2'", "Bw2", "Bw2'", "z2", "z2'"
                    ],
                ];
                foreach ($moveMap as $prefixer => $originMoves) {
                    if (array_search($tmp, $originMoves) !== false) {
                        $prefixes[] = $prefixer;
                        break;
                    }
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
                if (strpos('URFDLBEMSxyz', $matches[3]) !== false) {
                    $cube->$func($amount);
                } elseif (strpos('urfdlb', $matches[3]) !== false) {
                    $cube->$func($amount, 2, 2);
                }
            }
        }
    }


}
