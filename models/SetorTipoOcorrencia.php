<?php

namespace app\models;

use Yii;
use app\components\ClienteActiveRecord;
use app\models\OcorrenciaTipoProblema;

class SetorTipoOcorrencia extends ClienteActiveRecord
{
    public static function tableName()
    {
        return 'setor_tipos_ocorrencias';
    }

    public function rules()
    {
        return [
            [['setor_id', 'tipos_problemas_id'], 'required'],
            [['setor_id', 'tipos_problemas_id'], 'integer'],
            [['tipos_problemas_id'], 'exist', 'skipOnError' => true, 'targetClass' => OcorrenciaTipoProblema::className(), 'targetAttribute' => ['tipos_problemas_id' => 'id']],
            [['setor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Setor::className(), 'targetAttribute' => ['setor_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'setor_id' => 'Setor',
            'tipos_problemas_id' => 'Tipos de Ocorrência',
        ];
    }

    public function getTiposProblemas()
    {
        return $this->hasOne(OcorrenciaTipoProblema::className(), ['id' => 'tipos_problemas_id']);
    }

    public function getSetor()
    {
        return $this->hasOne(Setor::className(), ['id' => 'setor_id']);
    }
}
