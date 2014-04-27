<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "focos_transmissores".
 *
 * Estas são as colunas disponíveis na tabela "focos_transmissores":
 * @property integer $id
 * @property integer $inserido_por
 * @property integer $atualizado_por
 * @property integer $quarteirao_id
 * @property integer $tipo_imovel_id
 * @property integer $tipo_deposito_id
 * @property integer $especie_transmissor_id
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property string $data_entrada
 * @property string $data_exame
 * @property string $data_coleta
 * @property string $endereco
 * @property integer $quantidade_forma_aquatica
 * @property integer $quantidade_forma_adulta
 * @property integer $quantidade_ovos
 *
 * @property Usuario $inseridoPor
 * @property Usuario $atualizadoPor
 * @property BairroQuarteirao $quarteirao
 * @property ImovelTipo $tipoImovel
 * @property DepositoTipo $tipoDeposito
 * @property EspecieTransmissor $especieTransmissor
 */
class FocoTransmissor extends ActiveRecord 
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'focos_transmissores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inserido_por', 'quarteirao_id', 'tipo_imovel_id', 'tipo_deposito_id', 'especie_transmissor_id', 'endereco'], 'required'],
            [['quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'], 'integer', 'min' => 0],
            [['!inserido_por', '!atualizado_por'], 'exist', 'targetClass' => Usuario::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['quarteirao_id', 'exist', 'targetClass' => BairroQuarteirao::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['tipo_imovel_id', 'exist', 'targetClass' => ImovelTipo::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['tipo_deposito_id', 'exist', 'targetClass' => DepositoTipo::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['!data_cadastro', '!data_atualizacao', 'data_entrada', 'data_exame', 'data_coleta'], 'date'],
            [['endereco'], 'string', 'max' => 2048]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inserido_por' => 'Inserido por',
            'atualizado_por' => 'Atualizado por',
            'quarteirao_id' => 'Quarteirão',
            'tipo_imovel_id' => 'Tipo de Imóvel',
            'tipo_deposito_id' => 'Tipo de Depósito',
            'especie_transmissor_id' => 'Espécie de Transmissor',
            'data_cadastro' => 'Data de Cadastro',
            'data_atualizacao' => 'Data de Atualização',
            'data_entrada' => 'Data da Entrada',
            'data_exame' => 'Data do Exame',
            'data_coleta' => 'Data da Coleta',
            'endereco' => 'Endereço',
            'quantidade_forma_aquatica' => 'Qtde. Forma Aquática',
            'quantidade_forma_adulta' => 'Qtde. Forma Adulta',
            'quantidade_ovos' => 'Qtde. Ovos',
        ];
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getInseridoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getQuarteirao()
    {
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'quarteirao_id']);
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getTipoImovel()
    {
        return $this->hasOne(ImovelTipo::className(), ['id' => 'tipo_imovel_id']);
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getTipoDeposito()
    {
        return $this->hasOne(DepositoTipo::className(), ['id' => 'tipo_deposito_id']);
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getEspecieTransmissor()
    {
        return $this->hasOne(EspecieTransmissor::className(), ['id' => 'especie_transmissor_id']);
    }
}
