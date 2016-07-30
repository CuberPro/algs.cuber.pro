<?php
use yii\helpers\Url;
use app\utils\Url as UrlUtil;

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
    <img src="<?= UrlUtil::buildUrl('/visualcube/visualcube.php', $imgParams) ?>" alt="<?= $model['name'] ?>">
    <span><?= $model['name'] ?></span>
</a>
