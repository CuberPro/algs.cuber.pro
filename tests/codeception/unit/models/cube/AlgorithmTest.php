<?php

namespace tests\codeception\unit\models\cube;

use yii\codeception\TestCase;
use app\models\cube\Algorithm;
use Codeception\Specify;

class AlgorithmTest extends TestCase {
    use Specify;

    protected function _before() {
    }

    protected function _after() {
    }

    public function testConstructor() {
        $this->specify('Filter out illegal moves', function() {
            $testCases = [
                "R F3 2rw 3R sdf x2 4Rw2'" => "R x2 4Rw2'",
                "ur l f d' b x2 y' M2 3Ew s" => "x2 y' M2",
            ];
            foreach ($testCases as $input => $result) {
                $algo = new Algorithm($input);
                expect(strval($algo))->equals($result);
            }
        });

        $this->specify('Combine moves', function() {
            $testCases = [
                "R Rw2" => "R Rw2",
                "L R L2" => "L R L2",
                "F F2" => "F'",
                "R R' D U L" => "D U L",
                "R' L Lw L' D" => "R' L Lw L' D",
                "R F F2' D2'" => "R F' D2'",
                "R E2 E2'" => "R",
                "L L'" => "",
                "L R R' L2" => "L'",
                "U R F D L B x B" => "U R F D L B x B",
            ];
            foreach ($testCases as $input => $result) {
                $algo = new Algorithm($input);
                expect(strval($algo))->equals($result);
            }
        });
    }

    public function testGetReverse() {
        $this->specify('Reverse without prefixes', function() {
            $testCases = [
                "R U' R U R U R U' R' U' R2" => "R2' U R U R' U' R' U' R' U R'",
                "x' R U' R D2 R' U R D2 R2" => "R2' D2' R' U' R D2' R' U R' x",
                "Rw F2 B' B F'" => "F' Rw'",
            ];
            foreach ($testCases as $input => $result) {
                $algo = new Algorithm($input);
                expect(strval($algo->getReverse()))->equals($result);
            }
        });
        $this->specify('Reverse with prefixes', function() {
            $testCases = [
                "R U' R U R U R U' R' U' R2" => "R2' U R U R' U' R' U' R' U R'",
                "x' R U' R D2 R' U R D2 R2 x" => "x' R2' D2' R' U' R D2' R' U R' x",
                "Rw F2 B' B F'" => "x F' Rw'",
                "L R F x" => "F' R' L'",
                "x y R U R' U'" => "x y U R U' R' y' x'",
                "R U R' U' x L D2 y M z'" => "x y x' M' y' D2' L' x' U R U' R'",
                "R' U R' Dw' R' F' R2 U' R' U R' F R F" => "y F' R' F' R U' R U R2' F R Dw R U' R",
                "R' U R' U' y R' F' R2 U' R' U R' F R F" => "y F' R' F' R U' R U R2' F R y' U R U' R",
            ];
            foreach ($testCases as $input => $result) {
                $algo = new Algorithm($input);
                expect(strval($algo->getReverse(true)))->equals($result);
            }
        });
    }

}
