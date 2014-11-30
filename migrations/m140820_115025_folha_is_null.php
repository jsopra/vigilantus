<?php

use yii\db\Migration;

class m140820_115025_folha_is_null extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE boletins_rg ALTER COLUMN folha DROP NOT NULL");
    }

    public function safeDown()
    {
        echo "m140820_115025_folha_is_null cannot be reverted.\n";
        return false;
    }
}
