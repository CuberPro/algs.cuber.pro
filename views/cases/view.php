<?php

use yii\helpers\Url;
use yii\grid\GridView;
use app\assets\CaseViewAsset;

/* @var $this yii\web\View */
/* @var $model app\models\db\Cases */

$this->title = $model['name'];
$this->params['breadcrumbs'][] = [
    'label' => $cubeId,
    'url' => ['cubes/view', 'cubeId' => $cubeId],
];
$this->params['breadcrumbs'][] = [
    'label' => $subsetName,
    'url' => ['subsets/view', 'cubeId' => $cubeId, 'subsetName' => $subsetName],
];
$this->params['breadcrumbs'][] = $this->title;

CaseViewAsset::register($this);

$imgParams = [
    'size' => 150,
    'view' => isset($model['view']) ? $model['view'] : null,
    'fmt' => 'png',
    'pzl' => $model['size'],
    'bg' => 't',
    'fd' => isset($model['state']) ? $model['state'] : null,
];
?>
<div class="case-view">
    <div class="case-img">
        <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $model['name'] ?>">
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'class' => 'serial',
                ],
                'contentOptions' => [
                    'class' => 'serial',
                ],
            ],
            [
                'label' => 'Algorithm',
                'value' => 'text',
            ],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered algs-table',
        ],
        'emptyText' => Yii::t('app', 'No algorithms for this case yet.'),
    ]) ?>
</div>
