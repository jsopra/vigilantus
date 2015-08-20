<?php

use yii\db\Migration;

class m150820_193848_acertado_migrate_setores extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('setores', 'usuario_inseriu_id', 'inserido_por');
        $this->renameColumn('setores', 'datahora_inseriu', 'data_cadastro');
        $this->renameColumn('setores', 'usuario_alterou_id', 'atualizado_por');
        $this->renameColumn('setores', 'datahora_alterou', 'data_atualizacao');
    }

    public function safeDown()
    {
        echo "m150820_193848_acertado_migrate_setores cannot be reverted.\n";
        return false;
    }
}
