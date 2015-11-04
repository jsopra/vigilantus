<?php

use yii\db\Migration;

class m151013_173558_indice_unico extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_uk_bairros', 'bairros', ['nome', 'municipio_id'], true);
        $this->createIndex('idx_uk_bairro_categorias', 'bairro_categorias', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_bairro_quarteiroes', 'bairro_quarteiroes', ['numero_quarteirao', 'bairro_id', 'municipio_id'], true);
        //$this->createIndex('idx_uk_bairro_quarteiroes_2', 'bairro_quarteiroes', ['numero_quarteirao_2', 'bairro_id', 'municipio_id'], true);
        $this->createIndex('idx_uk_boletins_rg', 'boletins_rg', ['folha', 'data', 'cliente_id'], true);
        $this->createIndex('idx_uk_boletim_rg_fechamento', 'boletim_rg_fechamento', [ 'boletim_rg_id', 'imovel_tipo_id', 'imovel_lira', 'cliente_id'], true);
        $this->createIndex('idx_uk_deposito_tipos', 'deposito_tipos', ['sigla', 'cliente_id'], true);
        $this->createIndex('idx_uk_doencas', 'doencas', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_equipes', 'equipes', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_equipe_agentes', 'equipe_agentes', ['codigo', 'cliente_id'], true);
        $this->createIndex('idx_uk_especies_transmissores', 'especies_transmissores', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_especie_transmissor_doencas', 'especie_transmissor_doencas', ['doenca_id', 'especie_transmissor_id', 'cliente_id'], true);
        $this->createIndex('idx_uk_imovel_tipos', 'imovel_tipos', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_municipios', 'municipios', ['nome', 'sigla_estado'], true);
        $this->createIndex('idx_uk_ocorrencia_tipos_problemas', 'ocorrencia_tipos_problemas', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_ruas', 'ruas', ['nome', 'municipio_id'], true);
        $this->createIndex('idx_uk_setores', 'setores', ['nome', 'cliente_id'], true);
        $this->createIndex('idx_uk_setor_usuarios', 'setor_usuarios', ['setor_id', 'usuario_id'], true);
        $this->createIndex('idx_uk_social_hashtags', 'social_hashtags', ['termo', 'cliente_id'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_uk_bairros', 'bairros');
        $this->dropIndex('idx_uk_bairro_categorias', 'bairro_categorias');
        $this->dropIndex('idx_uk_bairro_quarteiroes', 'bairro_quarteiroes');
        $this->dropIndex('idx_uk_bairro_quarteiroes_2', 'bairro_quarteiroes');
        $this->dropIndex('idx_uk_boletins_rg', 'boletins_rg');
        $this->dropIndex('idx_uk_boletim_rg_fechamento', 'boletim_rg_fechamento');
        $this->dropIndex('idx_uk_deposito_tipos', 'deposito_tipos');
        $this->dropIndex('idx_uk_doencas', 'doencas');
        $this->dropIndex('idx_uk_equipes', 'equipes');
        $this->dropIndex('idx_uk_equipe_agentes', 'equipe_agentes');
        $this->dropIndex('idx_uk_especies_transmissores', 'especies_transmissores');
        $this->dropIndex('idx_uk_especie_transmissor_doencas', 'especie_transmissor_doencas');
        $this->dropIndex('idx_uk_imovel_tipos', 'imovel_tipos');
        $this->dropIndex('idx_uk_municipios', 'municipios');
        $this->dropIndex('idx_uk_ocorrencia_tipos_problemas', 'ocorrencia_tipos_problemas');
        $this->dropIndex('idx_uk_ruas', 'ruas');
        $this->dropIndex('idx_uk_setores', 'setores');
        $this->dropIndex('idx_uk_setor_usuarios', 'setor_usuarios');
        $this->dropIndex('idx_uk_social_hashtags', 'social_hashtags');
    }
}
