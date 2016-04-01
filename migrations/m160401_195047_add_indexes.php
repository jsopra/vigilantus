<?php

use yii\db\Migration;

class m160401_195047_add_indexes extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_bairros_municipio_id', 'bairros', ['municipio_id']);
        $this->createIndex('idx_municipios_slug', 'municipios', ['slug']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_bairros_municipio_id', 'bairros');
        $this->dropIndex('idx_municipios_slug', 'municipios');
    }
}
