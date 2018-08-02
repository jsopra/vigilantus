<?php

namespace app\models;
use app\components\ClienteActiveRecord;
use Yii;

/**
 * This is the model class for table "visita_imovel_depositos".
 *
 * @property integer $id
 * @property integer $visita_id
 * @property integer $tipo_deposito_id
 * @property integer $quantidade
 *
 * @property DepositoTipo $tipoDeposito
 * @property VisitaImovel $visita
 */
class VisitaImovelDeposito extends ClienteActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_imovel_depositos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visita_id', 'tipo_deposito_id'], 'required'],
            [['visita_id', 'tipo_deposito_id', 'quantidade'], 'integer'],
            [['tipo_deposito_id'], 'exist', 'skipOnError' => true, 'targetClass' => DepositoTipo::className(), 'targetAttribute' => ['tipo_deposito_id' => 'id']],
            [['visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaImovel::className(), 'targetAttribute' => ['visita_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_id' => 'Visita',
            'tipo_deposito_id' => 'Tipo do Depósito',
            'quantidade' => 'Quantidade',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDeposito()
    {
        return $this->hasOne(DepositoTipo::className(), ['id' => 'tipo_deposito_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisita()
    {
        return $this->hasOne(VisitaImovel::className(), ['id' => 'visita_id']);
    }
}
