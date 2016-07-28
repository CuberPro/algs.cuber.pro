<?php

use yii\db\Migration;
use app\rbac\UserGroupRule;

class m160727_165636_init_permissions_and_roles extends Migration {
    public function safeUp() {
        $auth = Yii::$app->authManager;

        // add editCase permission
        $editCase = $auth->createPermission('editCase');
        $editCase->description = 'Edit a Case';
        $auth->add($editCase);

        // add user group rule
        $userGroupRule = new UserGroupRule;
        $auth->add($userGroupRule);

        // add user role
        $user = $auth->createRole('user');
        $user->description = 'Normal users';
        $user->ruleName = $userGroupRule->name;
        $auth->add($user);

        // add admin role
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrators';
        $admin->ruleName = $userGroupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $editCase);
    }

    public function safeDown() {
        $auth = Yii::$app->authManager;

        $user = $auth->getRole('user');
        $admin = $auth->getRole('admin');
        $editCase = $auth->getPermission('editCase');
        $userGroupRule = $auth->getRule('userGroup');

        $auth->removeChild($admin, $editCase);
        $auth->removeChild($admin, $user);
        $auth->remove($admin);
        $auth->remove($user);
        $auth->remove($userGroupRule);
        $auth->remove($editCase);
    }
}
