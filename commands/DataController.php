<?php

namespace app\commands;

use yii\console\Controller;
use app\models\db\Subsets;
use app\models\db\Cases;
use app\models\db\CasesInSubset;
use app\models\db\Algs;
use app\models\db\AlgsForCase;
use app\models\cube\Algorithm;
use app\models\cube\CubeNNN;
use phpQuery;

/**
 * Importing, validating data
 */
class DataController extends Controller {

    public $defaultAction = 'import';

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
     * Import data from algdb.net
     */
    public function actionImport($conf) {
        $conf = require $conf;
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
                $this->importCases($cube, $subset, $id);
            }
        }
    }

    private function importCases($cube, $subset, $algdbSubsetId) {
        $url = $this->getUrl($algdbSubsetId);
        $count = 0;
        phpQuery::newDocumentFile($url);
        $trs = pq('tbody tr');
        foreach ($trs as $idx => $tr) {
            $it = pq($tr);
            $name = $it->find('td:first-child')->text();
            $algoText = $it->find('td:last-child')->text();
            $this->formatAlgoText($algoText);
            if (preg_match('/(\d+)$/', $name, $matches)) {
                $seq = intval($matches[1]);
                $alias = null;
            } else {
                $seq = $idx + 1;
                $alias = trim(preg_replace(sprintf('/^%s/i', $subset->name), '', $name));
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
            $caseInSubset = CasesInSubset::findOne($data);
            if ($caseInSubset == null) {
                $case = Cases::findOne(['state' => $state]);
                if (!$case) {
                    $case = new Cases([
                        'id' => md5($state),
                        'state' => $state,
                    ]);
                }
                Cases::getDb()->transaction(function ($db) use ($cube, $subset, $case, $seq, $alias, $caseInSubset) {
                    $success = $case->save();
                    if (!$success) {
                        var_dump($case->attributes);
                        exit;
                    }
                    $caseInSubset = new CasesInSubset([
                        'cube' => $cube,
                        'subset' => $subset->name,
                        'case' => $case->id,
                        'sequence' => $seq,
                        'alias' => $alias,
                    ]);
                    $success = $caseInSubset->save();
                    if (!$success) {
                        var_dump($caseInSubset->attributes);
                        exit;
                    }
                });
                $count++;
            }
            $this->importAlgs($cube, $subset, $algdbSubsetId, $caseInSubset, $name);
        }
        printf("URL: %s, total: %d, added: %d\n", $url, $trs->size(), $count);
    }

    private function importAlgs($cube, $subset, $algdbSubsetId, $caseInSubset, $algdbCaseName) {
        $url = $this->getUrl($algdbSubsetId, $algdbCaseName);
        $count = 0;
        $case = $caseInSubset->case0;
        phpQuery::newDocumentFile($url);
        $trs = pq('tbody tr');
        foreach ($trs as $tr) {
            $it = pq($tr);
            $algoText = $it->find('td:first-child a')->text();
            $this->formatAlgoText($algoText);
            if (empty($algoText)) {
                continue;
            }
            $algo = new Algorithm($algoText);
            $algoHash = md5(strval($algo));
            $data = [
                'alg' => $algoHash,
                'case' => $case->id,
            ];
            $algForCase = AlgsForCase::findOne($data);
            if ($algForCase == null) {
                $alg = Algs::findOne(['id' => $algoHash]);
                if (!$alg) {
                    $alg = new Algs([
                        'id' => $algoHash,
                        'text' => strval($algo),
                    ]);
                }
                Algs::getDb()->transaction(function ($db) use ($alg, $case, $algForCase) {
                    $success = $alg->save();
                    if (!$success) {
                        var_dump($alg->attributes);
                        exit;
                    }
                    $algForCase = new AlgsForCase([
                        'alg' => $alg->id,
                        'case' => $case->id,
                    ]);
                    $success = $algForCase->save();
                    if (!$success) {
                        var_dump($algForCase->attributes);
                        exit;
                    }
                });
                $count++;
            }
        }
        printf("\tURL: %s, total: %d, added: %d\n", $url, $trs->size(), $count);
    }

    private function formatAlgoText(&$algoText) {
        $algoText = trim($algoText);
        $algoText = explode("\n", $algoText);
        $algoText = $algoText[0];
        $algoText = trim($algoText);
        $algoText = str_replace(
            ['u', 'r', 'f', 'd', 'l', 'b', '’', '.'],
            ['Uw', 'Rw', 'Fw', 'Dw', 'Lw', 'Bw', "'", ''],
            $algoText
        );
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
            $arr[] = rawurlencode(trim($caseName));
        }
        return implode('/', $arr);
    }

    private function fetchSubsets() {
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