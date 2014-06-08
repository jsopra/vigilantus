<?php

use yii\db\Migration;

class m140607_164037_blog extends Migration
{
    public function safeUp()
    {
        $this->createTable('blog_post', array(
            'id' => 'pk',
            'data' => 'timestamp without time zone default now()',
            'titulo' => 'varchar not null',
            'descricao' => 'varchar',
            'texto' => 'text not null',
        ));
    }

    public function safeDown()
    {
        echo "m140607_164037_blog cannot be reverted.\n";
        return false;
    }
}
