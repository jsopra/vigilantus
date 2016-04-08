<?php
namespace app\models\report;

use app\models\Ocorrencia;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OcorrenciaAbertasReport extends Model
{
    public function attributeLabels()
    {
        return [
            'hash_acesso_publico' => 'Protocolo',
            'ocorrencia_tipo_problema_id' => 'Tipo do Problema',
            'qtde_dias_aberto' => 'Qtde. Dias em Aberto',
        ];
    }

    public function getDataProvider()
    {
        $query = Ocorrencia::find();
        $query->aberta();
        return new ActiveDataProvider(['query' => $query]);
    }

}
