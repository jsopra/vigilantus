<?php

use yii\db\Migration;

class m150604_214119_add_token_api_to_usuarios extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'usuarios',
            'token_api',
            'string'
        );

        // Gera os tokens únicos
        $rows = $this->db->masterPdo->query('SELECT id FROM usuarios')->fetchAll(PDO::FETCH_OBJ);
        foreach ($rows as $row) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $this->update('usuarios', ['token_api' => $token], 'id = ' . $row->id);
        }

        // Seta o campo como NOT NULL
        $this->db->pdo->query('ALTER TABLE usuarios ALTER COLUMN token_api SET NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('token_api');
    }
}
