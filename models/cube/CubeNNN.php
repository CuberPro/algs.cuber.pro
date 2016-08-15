<?php

namespace app\models\cube;

use yii\base\Model;

class CubeNNN extends Model {

    const MIN_SIZE = 2;
    const MAX_SIZE = 9;

    const U = 'u';
    const R = 'r';
    const F = 'f';
    const D = 'd';
    const L = 'l';
    const B = 'b';
    const NONE = 'n';

    private $stickers;
    private $size;

    public function __construct($size = 3, $stickers = null) {
        $size = intval($size);
        if ($size < self::MIN_SIZE || $size > self::MAX_SIZE) {
            $size = 3;
        }
        $this->size = $size;
        if (isset($stickers) && $this->setStickersString($stickers)) {
            return;
        }
        $this->reset();
    }

    public function __get($name) {
        $available = ['size' => true];
        if (isset($available[$name])) {
            return $this->$name;
        }
    }

    public function reset() {
        $size = $this->size;
        $this->stickers = [
            self::U => array_fill(0, $size * $size, self::U),
            self::R => array_fill(0, $size * $size, self::R),
            self::F => array_fill(0, $size * $size, self::F),
            self::D => array_fill(0, $size * $size, self::D),
            self::L => array_fill(0, $size * $size, self::L),
            self::B => array_fill(0, $size * $size, self::B),
        ];
    }

    public function apply(Algorithm $alg) {
        $alg->applyTo($this);
    }

    public function moveE($amount = 1, $shiftOut = 0) {
        $shiftOut = intval($shiftOut);
        if ($shiftOut < 0) {
            $shiftOut = 0;
        }
        $end = ($this->size >> 1) + 1 + $shiftOut;
        if ($end >= $this->size) {
            $end = $this->size - 1;
        }
        $start = $this->size + 1 - $end;
        if ($start <= $end) {
            $this->moveD($amount, $end, $start);
        }
        return $this;
    }

    public function moveM($amount = 1, $shiftOut = 0) {
        $shiftOut = intval($shiftOut);
        if ($shiftOut < 0) {
            $shiftOut = 0;
        }
        $end = ($this->size >> 1) + 1 + $shiftOut;
        if ($end >= $this->size) {
            $end = $this->size - 1;
        }
        $start = $this->size + 1 - $end;
        if ($start <= $end) {
            $this->moveL($amount, $end, $start);
        }
        return $this;
    }

    public function moveS($amount = 1, $shiftOut = 0) {
        $shiftOut = intval($shiftOut);
        if ($shiftOut < 0) {
            $shiftOut = 0;
        }
        $end = ($this->size >> 1) + 1 + $shiftOut;
        if ($end >= $this->size) {
            $end = $this->size - 1;
        }
        $start = $this->size + 1 - $end;
        if ($start <= $end) {
            $this->moveF($amount, $end, $start);
        }
        return $this;
    }


    public function moveR($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveX($amount, $this->size - $i);
        }
        return $this;
    }

    public function moveU($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveY($amount, $this->size - $i);
        }
        return $this;
    }

    public function moveF($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveZ($amount, $this->size - $i);
        }
        return $this;
    }

    public function moveD($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveY(4 - $amount, $i - 1);
        }
        return $this;
    }

    public function moveL($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveX(4 - $amount, $i - 1);
        }
        return $this;
    }

    public function moveB($amount = 1, $end = 1, $start = null) {
        $this->normalize($amount, $end, $start);
        for ($i = $start; $i <= $end; $i++) {
            $this->moveZ(4 - $amount, $i - 1);
        }
        return $this;
    }

    public function moveX($amount = 1, $slice = null) {
        if (!isset($slice)) {
            for ($i = 0; $i < $this->size; $i++) {
                $this->moveX($amount, $i);
            }
        } else {
            $size = $this->size;
            $this->normalize($amount);
            $x = intval($slice);
            if ($x < 0) {
                $x = 0;
            }
            if ($x > $size - 1) {
                $x = $size - 1;
            }
            for ($i = 0; $i < $amount; $i++) {
                if ($x == 0) {
                    $this->rotate(self::L, true);
                }
                if ($x == $size - 1) {
                    $this->rotate(self::R);
                }
                for ($j = 0; $j < $size; $j++) {
                    $a = $j * $size + $x; // U
                    $b = $a; // F
                    $c = $a; // D
                    $d = $size * ($size - 1 - $j) + ($size - 1 - $x); // B
                    $tmp = $this->stickers[self::U][$a];
                    $this->stickers[self::U][$a] = $this->stickers[self::F][$b];
                    $this->stickers[self::F][$b] = $this->stickers[self::D][$c];
                    $this->stickers[self::D][$c] = $this->stickers[self::B][$d];
                    $this->stickers[self::B][$d] = $tmp;
                }
            }
        }
        return $this;
    }

    public function moveY($amount = 1, $slice = null) {
        if (!isset($slice)) {
            for ($i = 0; $i < $this->size; $i++) {
                $this->moveY($amount, $i);
            }
        } else {
            $size = $this->size;
            $this->normalize($amount);
            $y = intval($slice);
            if ($y < 0) {
                $y = 0;
            }
            if ($y > $size - 1) {
                $y = $size - 1;
            }
            for ($i = 0; $i < $amount; $i++) {
                if ($y == 0) {
                    $this->rotate(self::D, true);
                }
                if ($y == $size - 1) {
                    $this->rotate(self::U);
                }
                for ($j = 0; $j < $size; $j++) {
                    $a = $size * ($size - 1 - $y) + $j; // F
                    $b = $a; // R
                    $c = $a; // B
                    $d = $a; // L
                    $tmp = $this->stickers[self::F][$a];
                    $this->stickers[self::F][$a] = $this->stickers[self::R][$b];
                    $this->stickers[self::R][$b] = $this->stickers[self::B][$c];
                    $this->stickers[self::B][$c] = $this->stickers[self::L][$d];
                    $this->stickers[self::L][$d] = $tmp;
                }
            }
        }
        return $this;
    }

    public function moveZ($amount = 1, $slice = null) {
        if (!isset($slice)) {
            for ($i = 0; $i < $this->size; $i++) {
                $this->moveZ($amount, $i);
            }
        } else {
            $size = $this->size;
            $this->normalize($amount);
            $z = intval($slice);
            if ($z < 0) {
                $z = 0;
            }
            if ($z > $size - 1) {
                $z = $size - 1;
            }
            for ($i = 0; $i < $amount; $i++) {
                if ($z == 0) {
                    $this->rotate(self::B, true);
                }
                if ($z == $size - 1) {
                    $this->rotate(self::F);
                }
                for ($j = 0; $j < $size; $j++) {
                    $a = $size * $z + $j; // U
                    $b = $size * ($size - 1 - $j) + $z; // L
                    $c = $size * ($size - 1 - $z) + ($size - 1 - $j); // D
                    $d = $size * $j + ($size - 1 - $z); // R
                    $tmp = $this->stickers[self::U][$a];
                    $this->stickers[self::U][$a] = $this->stickers[self::L][$b];
                    $this->stickers[self::L][$b] = $this->stickers[self::D][$c];
                    $this->stickers[self::D][$c] = $this->stickers[self::R][$d];
                    $this->stickers[self::R][$d] = $tmp;
                }
            }
        }
        return $this;
    }

    private function rotate($face, $counterClockwise = false) {
        $size = $this->size;
        $mid = $size >> 1;
        for ($shift = 0; $shift < $mid; $shift++) {
            $first = $shift * $size + $shift;
            $count = $size - 1 - ($shift << 1);
            for ($i = 0; $i < $count; $i++) {
                $a = $first + $i;
                $b = $size * ($size - 1 - $shift - $i) + $shift;
                $c = $size * ($size - 1 - $shift) + ($size - 1 - $shift - $i);
                $d = $size * ($shift + $i) + ($size - 1 - $shift);
                if ($counterClockwise) {
                    $tmp = $b;
                    $b = $d;
                    $d = $tmp;
                }
                $tmp = $this->stickers[$face][$a];
                $this->stickers[$face][$a] = $this->stickers[$face][$b];
                $this->stickers[$face][$b] = $this->stickers[$face][$c];
                $this->stickers[$face][$c] = $this->stickers[$face][$d];
                $this->stickers[$face][$d] = $tmp;
            }
        }
    }

    private function normalize(&$amount = null, &$end = null, &$start = null) {
        if (isset($amount)) {
            if ($amount < 0) {
                $amount = 4 - ((-$amount) & 3);
            }
            $amount &= 3;
        }
        if (isset($end)) {
            $end = intval($end);
            if ($end < 1) {
                $end = 1;
            }
            if ($end > $this->size) {
                $end = $this->size;
            }
            if (isset($start)) {
                $start = intval($start);
                if ($start > $end) {
                    $start = $end;
                }
                if ($start < 1) {
                    $start = 1;
                }
            } else {
                $start = 1;
            }
        }
    }

    public function isSolved() {
        foreach ($this->stickers as $face => $stickers) {
            $appeared = [];
            foreach ($stickers as $sticker) {
                $appeared[$sticker] = true;
            }
            unset($appeared[self::NONE]);
            if (count($appeared) > 1) {
                return false;
            }
        }
        return true;
    }

    public function getStickersString() {
        return implode(array_map('implode', $this->stickers));
    }

    public function setStickersString(string $stickers) {
        $rangeStr = self::U . self::R . self::F . self::D . self::L . self::B . self::NONE;
        $stickersPerFace = $this->size * $this->size;
        $stickersTotal = $stickersPerFace * 6;
        $regex = "/^[{$rangeStr}]{{$stickersTotal}}$/";
        if (!preg_match($regex, $stickers)) {
            return false;
        }
        $this->stickers = [
            self::U => str_split(substr($stickers, 0, $stickersPerFace)),
            self::R => str_split(substr($stickers, $stickersPerFace, $stickersPerFace)),
            self::F => str_split(substr($stickers, $stickersPerFace * 2, $stickersPerFace)),
            self::D => str_split(substr($stickers, $stickersPerFace * 3, $stickersPerFace)),
            self::L => str_split(substr($stickers, $stickersPerFace * 4, $stickersPerFace)),
            self::B => str_split(substr($stickers, $stickersPerFace * 5, $stickersPerFace)),
        ];
        return true;
    }

    public function __toString() {
        $str = '';
        for ($i = 0; $i < $this->size; $i++) {
            $str .= implode(array_fill(0, $this->size + 1, ' '));
            for ($j = 0; $j < $this->size; $j++) {
                $str .= $this->stickers[self::U][$i * $this->size + $j];
            }
            $str .= PHP_EOL;
        }
        $sides = [self::L, self::F, self::R, self::B];
        for ($i = 0; $i < $this->size; $i++) {
            for ($n = 0; $n < 4; $n++) {
                for ($j = 0; $j < $this->size; $j++) {
                    $str .= $this->stickers[$sides[$n]][$i * $this->size + $j];
                }
                $str .= ' ';
            }
            $str = rtrim($str, ' ');
            $str .= PHP_EOL;
        }
        for ($i = 0; $i < $this->size; $i++) {
            $str .= implode(array_fill(0, $this->size + 1, ' '));
            for ($j = 0; $j < $this->size; $j++) {
                $str .= $this->stickers[self::D][$i * $this->size + $j];
            }
            $str .= PHP_EOL;
        }
        return <<<XXX
{$this->size} x {$this->size} x {$this->size}
$str
XXX;
    }
}
