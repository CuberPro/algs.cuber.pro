<?php

namespace tests\codeception\unit\utils;

use yii\codeception\TestCase;
use Codeception\Specify;
use app\utils\Url;

class UrlTest extends TestCase {
    use Specify;

    protected function _before() {
    }

    protected function _after() {
    }

    public function testBuildUrl() {
        $this->specify('Add queries correctly', function() {
            $testCases = [
                [
                    'baseUrl' => '/visualcube/visualcube.php',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufdlbnnn',
                    ],
                    'expected' => '/visualcube/visualcube.php?view=plan&fd=rufdlbnnn',
                ],
                [
                    'baseUrl' => '/visualcube/visualcube.php?',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufdlbnnn',
                    ],
                    'expected' => '/visualcube/visualcube.php?view=plan&fd=rufdlbnnn',
                ],
                [
                    'baseUrl' => 'http://aaa/visualcube/visualcube.php?#',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufdlbnnn',
                    ],
                    'expected' => 'http://aaa/visualcube/visualcube.php?view=plan&fd=rufdlbnnn#',
                ],
                [
                    'baseUrl' => '/visualcube/visualcube.php#',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufdlbnnn',
                    ],
                    'expected' => '/visualcube/visualcube.php?view=plan&fd=rufdlbnnn#',
                ],
                [
                    'baseUrl' => '/visualcube/visualcube.php#aa',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufd lbnnn',
                    ],
                    'expected' => '/visualcube/visualcube.php?view=plan&fd=rufd%20lbnnn#aa',
                ],
                [
                    'baseUrl' => '//abc:233/visualcube/visualcube.php?a=1&b=%2f2&fd=3&d=+&cc[]=1&cc[a]=3#',
                    'params' => [
                        'view' => 'plan',
                        'fd' => 'rufdlbnnn',
                    ],
                    'expected' => '//abc:233/visualcube/visualcube.php?a=1&b=%2F2&fd=rufdlbnnn&d=%20&cc%5B0%5D=1&cc%5Ba%5D=3&view=plan#',
                ],
            ];
            foreach ($testCases as $case) {
                extract($case);
                $url = Url::buildUrl($baseUrl, $params);
                expect($url)->equals($expected);
            }
        });
    }

}
