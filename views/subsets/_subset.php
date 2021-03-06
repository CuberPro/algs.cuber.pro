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
$rowspan = count($model['algs']);
$rowspan = max($rowspan, 1);
?>
<tr>
    <td rowspan="<?= $rowspan ?>" class="case-name">
        <a href="<?= Url::toRoute(array_merge($linkHref, [$linkKey => $key])) ?>">
            <span><?= $model['name'] ?></span>
        </a>
    </td>
    <td rowspan="<?= $rowspan ?>" class="case-img">
        <a href="<?= Url::toRoute(array_merge($linkHref, [$linkKey => $key])) ?>">
            <img src="<?= UrlUtil::buildUrl('/visualcube/visualcube.php', $imgParams) ?>" alt="<?= $model['name'] ?>">
        </a>
    </td>
    <td class="alg">
        <?php
            $alg = array_shift($model['algs']);
            echo htmlspecialchars($alg['text']);
        ?>
    </td>
</tr>
<?php
while (count($model['algs']) > 0):
?>
<tr>
    <td class="alg">
        <?php
            $alg = array_shift($model['algs']);
            echo htmlspecialchars($alg['text']);
        ?>
    </td>
</tr>
<?php
endwhile;
?>
