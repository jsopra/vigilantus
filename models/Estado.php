<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "estados".
 *
 * Estas são as colunas disponíveis na tabela "estados":
 * @property integer $id
 * @property string $nome
 * @property string $uf
 */
class Estado extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estados';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // AVISO: só defina regras dos atributos que receberão dados do usuário
        return [
            [['nome', 'uf'], 'required'],
            [['nome'], 'string'],
            [['uf'], 'string', 'length' => 2],
            [['nome', 'uf'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'uf' => 'Unidade Federal',
        ];
    }

    /**
     * @return AtiveQuery
     */
    public function getMunicipios()
    {
        return $this->hasMany(Municipio::className(), ['sigla_estado' => 'uf']);
    }
}
