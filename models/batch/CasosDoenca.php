<?php

namespace app\models\batch;

use app\batch\Model;
use app\models\BairroQuarteirao;
use app\models\Bairro;
use app\models\Doenca;
use app\models\CasoDoenca;
use \IntlDateFormatter;

class CasosDoenca extends Model
{
    public function columnLabels()
    {
        return [
            'doenca' => 'Nome da Doença',
            'nome_paciente' => 'Nome do Paciente',
            'data_sintomas' => 'Data de Sintomas',
            'bairro' => 'Bairro',
            'quarteirao' => 'Quarteirão',
        ];
    }

    public function columnHints()
    {
        return [];
    }

    public function insert($row, $userId = null, $clienteId = null)
    {
        $bairro = Bairro::find()->doNome($row->getValue('bairro'))->one();
        if(!$bairro) {
            $row->addError('Bairro não localizado');
            return false;
        }

        $bairroQuarteirao = BairroQuarteirao::find()->doBairro($bairro->id)->dosNumeros($row->getValue('quarteirao'))->one();
        if(!$bairroQuarteirao) {
            $row->addError('Quarteirão não localizado');
            return false;
        }

        $doenca = Doenca::find()->doNome($row->getValue('doenca'))->one();
        if(!$doenca) {
            $row->addError('Doença não localizada');
            return false;
        }

        $caso = new CasoDoenca;

        $formatter = new IntlDateFormatter(
            \Yii::$app->language,
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE
        );

        $caso->nome_paciente = $row->GetValue('nome_paciente');
        $caso->data_sintomas = $row->GetValue('data_sintomas');
        $caso->data_cadastro = date('Y-m-d', $formatter->parse($row->getValue('data_cadastro')));
        $caso->data_atualizacao = date('Y-m-d', $formatter->parse($row->getValue('data_atualizacao')));
        $caso->bairro_quarteirao_id = $bairroQuarteirao->id;
        $caso->inserido_por = $userId ? $userId : \Yii::$app->user->identity->id;
        $caso->atualizado_por = $userId ? $userId : \Yii::$app->user->identity->id;
        return $caso->save();
    }
}
