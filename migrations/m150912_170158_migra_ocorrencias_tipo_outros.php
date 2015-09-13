<?php

use yii\db\Migration;

class m150912_170158_migra_ocorrencias_tipo_outros extends Migration
{
    public function safeUp()
    {
        // Pega os tipos "Outros"
        $stmt = $this->db->pdo->query('
            SELECT id
            FROM ocorrencia_tipos_problemas
            WHERE LOWER(nome) = \'outros\'
        ');

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            // Muda as ocorrências desse tipo pra NULL
            $this->db->pdo->query('
                UPDATE ocorrencias
                SET
                    ocorrencia_tipo_problema_id = NULL,
                    descricao_outro_tipo_problema = \'Outros\'
                WHERE
                    ocorrencia_tipo_problema_id = ' . $row->id . '
                    OR ocorrencia_tipo_problema_id IS NULL
            ');
            // Remove o tipo "Outros"
            $this->delete('ocorrencia_tipos_problemas', 'id = ' . $row->id);
        }
    }

    public function safeDown()
    {
        echo "m150912_170158_migra_ocorrencias_tipo_outros cannot be reverted.\n";
        return false;
    }
}
