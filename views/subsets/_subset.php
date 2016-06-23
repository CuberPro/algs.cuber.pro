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
$imgUrl = '/visualcube/visualcube.php?' . http_build_query($imgParams);
$MAX_ALGS_SHOW = 4;
$rowspan = count($model['algs']);
$rowspan = max($rowspan, 1);
$rowspan = min($rowspan, $MAX_ALGS_SHOW);
$showedCount = 0;
?>
<tr>
    <td rowspan="<?= $rowspan ?>" class="case-name">
        <a href="<?= Url::toRoute(array_merge($linkHref, [$linkKey => $key])) ?>">
            <span><?= $model['name'] ?></span>
        </a>
    </td>
    <td rowspan="<?= $rowspan ?>" class="case-img">
        <a href="<?= Url::toRoute(array_merge($linkHref, [$linkKey => $key])) ?>">
            <img src="<?= Url::toRoute(array_merge(['/visualcube/visualcube.php'], $imgParams)) ?>" alt="<?= $model['name'] ?>">
        </a>
    </td>
    <td class="alg">
        <?php
            $alg = array_shift($model['algs']);
            echo htmlspecialchars($alg['text']);
            $showedCount++;
        ?>
    </td>
</tr>
<?php
while (count($model['algs']) > 0 && $showedCount < $MAX_ALGS_SHOW):
?>
<tr>
    <td class="alg">
        <?php
            $alg = array_shift($model['algs']);
            echo htmlspecialchars($alg['text']);
            $showedCount++;
        ?>
    </td>
</tr>
<?php
endwhile;
?>
