<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\EditCaseAsset;

EditCaseAsset::register($this);

$this->title = 'Edit Case';
$imgParams = [
    'size' => 150,
    'view' => isset($case['subsets'][0]['view']) ? $case['subsets'][0]['view'] : null,
    'fmt' => 'png',
    'pzl' => $case['cube']['size'],
    'bg' => 't',
    'fd' => isset($case['state']) ? $case['state'] : null,
];
?>

<div id="case" class="edit-case clearfix" data-case-id="<?= $case['id'] ?>">
    <div class="case-info clearfix">
        <div class="pull-left text-center">
            <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $case['id'] ?>">
            <?php if(isset($imgParams['view'])): unset($imgParams['view']); ?>
                <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $case['id'] ?>">
                <?php $imgParams['r'] = 'x180y-45x-34'; ?>
                <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $case['id'] ?>">
            <?php endif; ?>
        </div>
        <div class="pull-left">
            <?= Html::ul($case['subsets'], [
                'item' => function ($item, $index) {
                    return Html::tag(
                        'li',
                        Html::a(
                            $item['name'],
                            ['subsets/view', 'cubeId' => $item['cube'], 'subsetName' => $item['name']]
                        ),
                        [
                            'class' => 'pull-left',
                        ]
                    );
                },
                'class' => 'list-inline',
            ]) ?>
        </div>
    </div>
    <div class="colors col-xs-12 col-sm-2 col-lg-1">
        <?= Html::ul(['u', 'r', 'f', 'd', 'l', 'b', 'n'], [
            'item' => function ($item, $index) {
                return Html::tag(
                    'li',
                    Html::radio(
                        'color',
                        $item === 'n',
                        [
                            'value' => $item,
                            'id' => 'color-' . $item,
                        ]
                    ) . Html::label(
                        strtoupper($item),
                        'color-' . $item,
                        [
                            'class' => 'select-color ' . $item,
                        ]
                    ),
                    [
                        'class' => 'color-selector col-xs-3 col-sm-12',
                    ]
                );
            },
            'class' => 'list-unstyled',
        ]) ?>
    </div>
    <div class="editor col-xs-12 col-sm-10 col-md-6">
        <?php
            $size = $case['cube']['size'];
            $state = $case['state'];
            $offsets = [4, 2, 1, 5];
            $names = ['l', 'f', 'r', 'b'];
        ?>
        <div class="row u">
            <div class="face col-xs-offset-3 col-xs-3" data-face="u">
                <?php for ($i = 0, $idx = 0; $i < $size; $i++): ?>
                <div class="sticker-row">
                    <?php for ($j = 0; $j < $size; $j++, $idx++): ?>
                        <div class="sticker" data-sticker="<?= $state{$idx} ?>"></div>
                    <?php endfor; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="row m">
            <?php for ($face = 0; $face < 4; $face++): ?>
                <div class="face col-xs-3" data-face="<?= $names[$face] ?>">
                    <?php for ($i = 0, $idx = $offsets[$face] * $size * $size; $i < $size; $i++): ?>
                    <div class="sticker-row">
                        <?php for ($j = 0; $j < $size; $j++, $idx++): ?>
                            <div class="sticker" data-sticker="<?= $state{$idx} ?>"></div>
                        <?php endfor; ?>
                    </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
        <div class="row d">
            <div class="face col-xs-offset-3 col-xs-3" data-face="d">
                <?php for ($i = 0, $idx = 3 * $size * $size; $i < $size; $i++): ?>
                <div class="sticker-row">
                    <?php for ($j = 0; $j < $size; $j++, $idx++): ?>
                        <div class="sticker" data-sticker="<?= $state{$idx} ?>"></div>
                    <?php endfor; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <div id="err-msg" class="alert col-xs-12 text-center">
        <button class="close">&times;</button>
        <span></span>
    </div>
    <div class="save-btn col-xs-12 text-center">
        <button class="btn btn-primary" id="save">Save</button>
    </div>
</div>
