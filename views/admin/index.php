<?php
/* @var $this yii\web\View */
use app\assets\CubeExpandAsset;

CubeExpandAsset::register($this);
?>
<h1>admin/index</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>


<table class="cube-expand">
    <tbody>
        <tr>
            <td rowspan="<?= $cubeSize ?>" colspan="<?= $cubeSize ?>"></td>
            <?php for ($i = 0; $i < $cubeSize; $i++):
                if ($i > 0): ?>
                    <tr>
                <?php endif; ?>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{$i * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
                    <?php if ($i == 0): ?><td rowspan="<?= $cubeSize ?>" colspan="<?= $cubeSize * 2 ?>"></td><?php endif; ?>
                <?php if ($i > 0) : ?>
                    </tr>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < $cubeSize; $i++): ?>
            <tr>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{($i + 4 * $cubeSize) * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{($i + 2 * $cubeSize) * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{($i + $cubeSize) * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{($i + 5 * $cubeSize) * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
        <tr>
            <td rowspan="<?= $cubeSize ?>" colspan="<?= $cubeSize ?>"></td>
            <?php for ($i = 0; $i < $cubeSize; $i++):
                if ($i > 0): ?>
                    <tr>
                <?php endif; ?>
                <?php for ($j = 0; $j < $cubeSize; $j++): ?>
                    <td class="<?= $cubeString{($i + 3 * $cubeSize) * $cubeSize + $j} ?>"></td>
                <?php endfor; ?>
                    <?php if ($i == 0): ?><td rowspan="<?= $cubeSize ?>" colspan="<?= $cubeSize * 2 ?>"></td><?php endif; ?>
                <?php if ($i > 0) : ?>
                    </tr>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
    </tbody>
</table>