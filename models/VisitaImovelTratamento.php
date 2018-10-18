<?php

namespace app\models;
use app\components\ClienteActiveRecord;
use Yii;

/**
 * This is the model class for table "visita_imovel_tratamentos".
 *
 * @property integer $id
 * @property integer $visita_id
 * @property integer $focal_imovel_tratamento
 * @property integer $focal_larvicida_tipo
 * @property integer $focal_larvicida_qtde_gramas
 * @property integer $focal_larvicida_qtde_dep_tratado
 * @property integer $perifocal_adulticida_tipo
 * @property integer $perifocal_adulticida_qtde_cargas
 *
 * @property VisitaImovel $visita
 */

class VisitaImovelTratamento extends ClienteActiveRecord
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
            [['visita_id'], 'required'],
            [['visita_id', 'focal_imovel_tratamento', 'focal_larvicida_tipo', 'focal_larvicida_qtde_dep_tratado', 'perifocal_adulticida_tipo'], 'integer'],
            [['visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaImovel::className(), 'targetAttribute' => ['visita_id' => 'id']],
            [['focal_larvicida_qtde_gramas', 'perifocal_adulticida_qtde_cargas'], 'number'],
            ['perifocal_adulticida_tipo', 'in', 'range' => VisitaTipoAdulticida::getIDS()],
            ['focal_larvicida_tipo', 'in', 'range' => VisitaTipoLarvicida::getIDS()],
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
            'focal_imovel_tratamento' => 'Focal Imovel Tratamento',
            'focal_larvicida_tipo' => 'Focal Larvicida Tipo',
            'focal_larvicida_qtde_gramas' => 'Focal Larvicida Qtde Gramas',
            'focal_larvicida_qtde_dep_tratado' => 'Focal Larvicida Qtde Dep Tratado',
            'perifocal_adulticida_tipo' => 'Perifocal Adulticida Tipo',
            'perifocal_adulticida_qtde_cargas' => 'Perifocal Adulticida Qtde Cargas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisita()
    {
        return $this->hasOne(VisitaImovel::className(), ['id' => 'visita_id']);
    }
}
