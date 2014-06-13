<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "especies_transmissores".
 *
 * Estas são as colunas disponíveis na tabela 'especies_transmissores':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property integer $qtde_metros_area_foco
 * @property integer $qtde_dias_permanencia_foco
 * @property string $cor_foco_no_mapa
 */
class EspecieTransmissor extends ActiveRecord
{
    
    const COR_FOCO_DEFAULT = '#000000';
    
    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'especies_transmissores';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return [
            [['municipio_id', 'qtde_metros_area_foco', 'qtde_dias_permanencia_foco', 'nome'], 'required'],
            [['cor_foco_no_mapa'], 'safe'],
            [['cor_foco_no_mapa'], 'string', 'max' => 7, 'skipOnEmpty' => true],
            ['municipio_id', 'exist', 'targetClass' => Municipio::className(), 'targetAttribute' => 'id'],
            ['nome', 'unique', 'compositeWith' => 'municipio_id'],
            [['qtde_metros_area_foco', 'qtde_dias_permanencia_foco'], 'integer'],
        ];
    }
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'municipio_id' => 'Município',
            'nome' => 'Nome',
            'qtde_metros_area_foco' => 'Área de foco (metros)',
            'qtde_dias_permanencia_foco' => 'Permanência do foco (dias)',
            'cor_foco_no_mapa' => 'Cor do foco no Mapa',
        );
    }
    
    /**
     * Busca cor do foco no mapa, considerando o default em caso de null para a espécie
     * @return string 
     */
    public function getCor() {
        return $this->cor_foco_no_mapa ? $this->cor_foco_no_mapa : self::COR_FOCO_DEFAULT;
    }
}
