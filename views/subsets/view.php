<?php

use app\assets\SubsetListAsset;
use yii\widgets\ListView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\db\Subsets */

$this->title = $subsetName;
$this->params['breadcrumbs'][] = [
    'label' => $cubeId,
    'url' => ['cubes/view', 'cubeId' => $cubeId],
];
$this->params['breadcrumbs'][] = $this->title;

SubsetListAsset::register($this);

$listLayout = <<<XXX
<div class="panel-heading">{summary}</div>
<div class="panel-body">
    <table class="table table-bordered subset-list">
        <thead>
            <tr class="info">
                <th class="case text-center" colspan="2">Case</th>
                <th class="alg">Algorithms</th>
            </tr>
        </thead>
        <tbody>{items}</tbody>
    </table>
</div>
XXX;
?>
<div class="subsets-view">

    <?= ListView::widget([
        'layout' => $listLayout,
        'summary' => Yii::t('app', 'Cases List'),
        'dataProvider' => $dataProvider,
        'itemView' => '_subset',
        'options' => [
            'class' => 'panel panel-primary',
            'id' => 'case-list',
        ],
        'itemOptions' => [
            'tag' => null,
        ],
        'viewParams' => [
            'imgParams' => [
            ],
            'linkHref' => [
                'cases/view',
                'cubeId' => $cubeId,
                'subsetName' => $subsetName,
            ],
            'linkKey' => 'caseName',
        ],
        'emptyText' => Yii::t('app', 'No Cases Found'),
        'emptyTextOptions' => [
            'class' => 'panel-body label-info empty',
        ],
    ]) ?>

</div>
