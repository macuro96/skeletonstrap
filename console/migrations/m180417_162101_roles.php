<?php

use yii\db\Migration;

use backend\components\PermisosUsuarios;

/**
 * Class m180411_172514_init_rbac
 */
class m180417_162101_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180411_172514_init_rbac cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        PermisosUsuarios::up();
    }

    public function down()
    {
        PermisosUsuarios::down();
    }
}
