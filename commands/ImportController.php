<?php

namespace app\commands;

use yii\console\Controller;
use app\models\db\Subsets;
use app\models\db\Cases;
use app\models\cube\Algorithm;
use app\models\cube\CubeNNN;
use phpQuery;

/**
 * Import data from sources, currently supporting algdb.net
 */
class ImportController extends Controller {

    public $defaultAction = 'algdb';

    const URL_BASE = 'http://algdb.net';

    private static $STICKERS_MAP = [
        '222' => [
            'oll' => 'uuuunnrrnnffddddnnllnnbb',
        ],
        '333' => [
            'oll' => 'uuuuuuuuunnnrrrrrrnnnffffffdddddddddnnnllllllnnnbbbbbb',
            'cmll' => 'ununnnunurnrrrrrrrfnffnffnfdnddnddndlnlllllllbnbbnbbnb',
            'coll' => 'uuuuuuuuurnrrrrrrrfnfffffffdddddddddlnlllllllbnbbbbbbb',
            'f2l' => 'nnnnunnnnnnnrrrrrrnnnffffffdddddddddnnnllllllnnnbbbbbb',
            'ollcp' => 'uuuuuuuuurnrrrrrrrfnfffffffdddddddddlnlllllllbnbbbbbbb',
            'vrls' => 'uuuuuuuuunnnrrrrrrnnnffffffdddddddddnnnllllllnnnbbbbbb',
            'wvls' => 'uuuuuuuuunnnrrrrrrnnnffffffdddddddddnnnllllllnnnbbbbbb',
        ],
    ];

    /**
     * grab content from algdb.net
     */
    public function actionAlgdb($conf) {
        $conf = require($conf);
        foreach ($conf as $cube => $subsets) {
            $cube = strval($cube);
            foreach ($subsets as $id => $name) {
                $data = [
                    'name' => $name,
                    'cube' => $cube,
                ];
                $subset = Subsets::findOne($data);
                if ($subset == null) {
                    $subset = new Subsets;
                    $subset->load(['Subsets' => $data]);
                    $subset->view = 'plan';
                    $subset->save();
                }
                $url = $this->getUrl($id);
                $this->getCases($url, $cube, $subset);
                // break 2;
            }
        }
        echo "\n";
    }

    private function getCases($url, $cube, $subset) {
        phpQuery::newDocumentFile($url);
        $trs = pq('tbody tr');
        foreach ($trs as $idx => $tr) {
            $it = pq($tr);
            $name = $it->find('td:first-child')->text();
            $algoText = $it->find('td:last-child')->text();
            $algoText = trim($algoText);
            $algoText = explode("\n", $algoText);
            $algoText = $algoText[0];
            $algoText = trim($algoText);
            $algoText = str_replace(['u', 'r', 'f', 'd', 'l', 'b', '’'], ['Uw', 'Rw', 'Fw', 'Dw', 'Lw', 'Bw', "'"], $algoText);
            if (preg_match("/(\d+)$/", $name, $matches)) {
                $seq = intval($matches[1]);
                $alias = null;
            } else {
                $seq = $idx + 1;
                $alias = trim(preg_replace("/^{$subset->name}/i", '', $name));
            }
            $algo = new Algorithm($algoText);
            $reverse = $algo->getReverse(true);
            $c = $this->getNewCube($cube, $subset->name);
            $c->apply($reverse);
            $state = $c->getStickersString();
            $data = [
                'cube' => $cube,
                'subset' => $subset->name,
                'sequence' => $seq,
            ];
            $case = Cases::findOne($data);
            if ($case == null) {
                $case = new Cases;
                $case->load(['Cases' => $data]);
                $case->alias = $alias;
                $case->state = $state;
                $success = $case->save();
                if (!$success) {
                    var_dump($data);
                    exit;
                }
            }
            // exit;
        }
        echo "\r                                                                ";
        echo "\rURL: $url, count: {$trs->size()}";
    }

    private function getNewCube($id, $subset) {
        $cube = new CubeNNN(intval(substr($id, 0, 1)));
        $tmp = preg_split('/\s+/', strtolower($subset));
        foreach ($tmp as $set) {
            $str = isset(self::$STICKERS_MAP[$id][$set]) ? self::$STICKERS_MAP[$id][$set] : null;
            if ($str != null) {
                $cube->setStickersString($str);
                break;
            }
        }
        return $cube;
    }

    private function getUrl($subsetId, $caseName = null) {
        $arr = [
            self::URL_BASE,
            'Set',
            rawurlencode($subsetId),
        ];
        if (isset($caseName)) {
            $arr[] = rawurlencode($caseName);
        }
        return implode('/', $arr);
    }

    private function getSubsets() {
        $doc = phpQuery::newDocumentFile(self::URL_BASE);
        $subsets = pq('span.shortcut-label');
        $tmp = [];
        foreach ($subsets as $subset) {
            $name = trim($subset->textContent);
            $tmp[$name] = $name;
        }
        file_put_contents(__DIR__ . '/subsets.php', var_export($tmp, true));
    }
}