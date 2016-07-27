<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class UserGroupRule extends Rule {

    public $name = 'userGroup';

    private $groupContains = [
        'admin' => ['user'],
    ];

    public function execute($user, $item, $params) {
        if (!Yii::$app->user->isGuest) {
            $userGroup = Yii::$app->user->identity->role;
            if ($userGroup == $item->name) {
                return true;
            }
            if (isset($this->groupContains[$userGroup])) {
                foreach ($this->groupContains[$userGroup] as $group) {
                    if ($group == $item->name) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
