<?php

use yii\db\Migration;

class m141112_091351_brasao_em_municipio extends Migration
{
    public function safeUp()
    {
    	$this->addColumn('municipios', 'brasao', 'varchar');

    	$this->execute("UPDATE municipios SET brasao='chapeco.jpg' WHERE id = 1");
    }

    public function safeDown()
    {
        echo "m141112_091351_brasao_em_municipio cannot be reverted.\n";
        return false;
    }
}
