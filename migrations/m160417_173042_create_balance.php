<?php

use yii\db\Migration;

class m160417_173042_create_balance extends Migration
{
    public function up()
    {
        $this->createTable('balance', [
            'user_id' => $this->integer(11)->unique(),
            'balance' => $this->string(10)->defaultValue(0),
            'modified_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('balance');
    }
}
