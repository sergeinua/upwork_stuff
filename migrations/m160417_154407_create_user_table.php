<?php

use yii\db\Migration;

class m160417_154407_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->integer(11)->primaryKey(),
            'username' => $this->string(50)->notNull()->unique(),
            'password' => $this->string(60)->notNull(),
            'authKey' => $this->string(60)->notNull(),
            'created_at' => $this->string(11)->notNull(),
            'modified_at' => $this->string(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
