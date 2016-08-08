<?php

use yii\db\Migration;

class m151210_185812_create_estados extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'estados',
            [
                'id' => 'pk',
                'nome' => 'string NOT NULL',
                'uf' => 'char(2) NOT NULL',
            ]
        );
        $this->insert('estados', ['nome' => 'Acre', 'uf' => 'AC']);
        $this->insert('estados', ['nome' => 'Alagoas', 'uf' => 'AL']);
        $this->insert('estados', ['nome' => 'Amazonas', 'uf' => 'AM']);
        $this->insert('estados', ['nome' => 'Amapá', 'uf' => 'AP']);
        $this->insert('estados', ['nome' => 'Bahia', 'uf' => 'BA']);
        $this->insert('estados', ['nome' => 'Ceará', 'uf' => 'CE']);
        $this->insert('estados', ['nome' => 'Distrito Federal', 'uf' => 'DF']);
        $this->insert('estados', ['nome' => 'Espírito Santo', 'uf' => 'ES']);
        $this->insert('estados', ['nome' => 'Goiás', 'uf' => 'GO']);
        $this->insert('estados', ['nome' => 'Maranhão', 'uf' => 'MA']);
        $this->insert('estados', ['nome' => 'Minas Gerais', 'uf' => 'MG']);
        $this->insert('estados', ['nome' => 'Mato Grosso do Sul', 'uf' => 'MS']);
        $this->insert('estados', ['nome' => 'Mato Grosso', 'uf' => 'MT']);
        $this->insert('estados', ['nome' => 'Pará', 'uf' => 'PA']);
        $this->insert('estados', ['nome' => 'Paraíba', 'uf' => 'PB']);
        $this->insert('estados', ['nome' => 'Pernambuco', 'uf' => 'PE']);
        $this->insert('estados', ['nome' => 'Piauí', 'uf' => 'PI']);
        $this->insert('estados', ['nome' => 'Paraná', 'uf' => 'PR']);
        $this->insert('estados', ['nome' => 'Rio de Janeiro', 'uf' => 'RJ']);
        $this->insert('estados', ['nome' => 'Rio Grande do Norte', 'uf' => 'RN']);
        $this->insert('estados', ['nome' => 'Rondônia', 'uf' => 'RO']);
        $this->insert('estados', ['nome' => 'Roraima', 'uf' => 'RR']);
        $this->insert('estados', ['nome' => 'Rio Grande do Sul', 'uf' => 'RS']);
        $this->insert('estados', ['nome' => 'Santa Catarina', 'uf' => 'SC']);
        $this->insert('estados', ['nome' => 'Sergipe', 'uf' => 'SE']);
        $this->insert('estados', ['nome' => 'São Paulo', 'uf' => 'SP']);
        $this->insert('estados', ['nome' => 'Tocantins', 'uf' => 'TO']);
    }

    public function safeDown()
    {
        $this->dropTable('estados');
    }
}
