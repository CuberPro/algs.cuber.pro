<?php

use yii\db\Migration;

/**
 * Handles adding role to table `Users`.
 */
class m160727_163801_add_role_column_to_users_table extends Migration {
    /**
     * @inheritdoc
     */
    public function up() {
        $this->addColumn('Users', 'role',
            $this->string(10)->notNull()->defaultValue('user')
            ->comment('the role of the user')->after('password')
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropColumn('Users', 'role');
    }
}
