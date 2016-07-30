<?php
use yii\helpers\Url;

$imgParams = array_merge([
    'size' => 150,
    'view' => isset($model['view']) ? $model['view'] : null,
], $imgParams, [
    'fmt' => 'png',
    'pzl' => $model['size'],
    'bg' => 't',
    'fd' => isset($model['state']) ? $model['state'] : null,
]);

?>
<a href="<?= Url::toRoute(array_merge($linkHref, [$linkKey => $key])) ?>" class="btn btn-default">
    <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $model['name'] ?>">
    <span><?= $model['name'] ?></span>
</a>
