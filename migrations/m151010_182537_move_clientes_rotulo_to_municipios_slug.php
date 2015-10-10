<?php

use yii\db\Expression;
use yii\db\Migration;
use yii\helpers\Inflector;

class m151010_182537_move_clientes_rotulo_to_municipios_slug extends Migration
{
    public function safeUp()
    {
        $this->addColumn('municipios', 'slug', 'string');
        $stmt = $this->db->masterPdo->query('SELECT * FROM municipios');
        while ($municipio = $stmt->fetch(PDO::FETCH_OBJ)) {
            $slug = Inflector::slug($municipio->nome . ' ' . $municipio->sigla_estado);
            $this->update('municipios', ['slug' => $slug], 'id = ' . $municipio->id);
        }
        $this->db->pdo->query('ALTER TABLE municipios ALTER COLUMN slug SET NOT NULL');
        $this->dropColumn('clientes', 'rotulo');
    }

    public function safeDown()
    {
        $this->addColumn('clientes', 'rotulo', 'string');
        $stmt = $this->db->masterPdo->query('SELECT * FROM municipios');
        while ($municipio = $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->update('clientes', ['rotulo' => $municipio->slug], 'municipio_id = ' . $municipio->id);
        }
        $this->db->pdo->query('ALTER TABLE clientes ALTER COLUMN rotulo SET NOT NULL');
        $this->dropColumn('municipios', 'slug');
    }
}
