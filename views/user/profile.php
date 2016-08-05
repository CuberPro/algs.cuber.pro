<?php

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Url;
use app\assets\UserProfileAsset;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
$this->params['logoutU'] = '/';

UserProfileAsset::register($this);

?>

<div class="user-profile">
    <div class="user-info col-sm-5 col-md-4 col-lg-3">
        <div>
            <span class="info-title">Name: </span>
            <span class="info-value"><?= $user->name ?></span>
        </div>
        <div>
            <span class="info-title">Email: </span>
            <span class="info-value"><?= $user->email ?></span>
        </div>
        <div>
            <span class="info-title">WCA ID: </span>
            <span class="info-value">
                <?php if ($user->wcaid != null): ?>
                    <a target="_blank" href="https://www.worldcubeassociation.org/results/p.php?i=<?= $user->wcaid ?>">
                        <img class="wca-logo" src="/img/WCAlogo_notext.svg" alt="WCA"><?= $user->wcaid ?>
                    </a>
                <?php else: ?>
                    <span class="empty">(none)</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
    <div class="oauth col-sm-7 col-md-6">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Auth Site</th>
                    <th>Site Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client->title ?></td>
                        <td>
                            <?php if (isset($userClients[$client->name])): ?>
                                <span><?= $userClients[$client->name]['source_name'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($userClients[$client->name])): ?>
                                <span><a class="btn btn-warning btn-xs revoke" href="#" data-source="<?= $client->name ?>">Disconnect</a></span>
                            <?php else: ?>
                                <span><a class="btn btn-success btn-xs auth" href="<?= Url::toRoute(['oauth/auth', 'authclient' => $client->name, 'u' => Url::to('')]) ?>">Connect</a></span>
                            <?php endif; ?>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </ul>
    </div>
</div>
