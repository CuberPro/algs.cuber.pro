<?php

use app\assets\CubeSetAsset;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Subsets');
$this->params['breadcrumbs'][] = $cubeId;

CubeSetAsset::register($this);

$listLayout = <<<XXX
<div class="panel-heading">{summary}</div>
<div class="panel-body"><div class="row">{items}</div></div>
XXX;
?>

<div class="subsets-list">
    <?= ListView::widget([
        'layout' => $listLayout,
        'summary' => Yii::t('app', 'Subsets List'),
        'dataProvider' => $dataProvider,
        'itemView' => '_cube',
        'options' => [
            'class' => 'panel panel-primary',
            'id' => 'cube-list',
        ],
        'itemOptions' => [
            'class' => 'cube col-xs-6 col-sm-4 col-md-3 col-lg-2',
        ],
        'viewParams' => [
            'imgParams' => [
            ],
            'linkHref' => [
                'subsets/view',
                'cubeId' => $cubeId,
            ],
            'linkKey' => 'subsetName',
        ],
        'emptyText' => Yii::t('app', 'No Subsets Found'),
        'emptyTextOptions' => [
            'class' => 'panel-body label-info empty',
        ],
    ]) ?>
</div>
