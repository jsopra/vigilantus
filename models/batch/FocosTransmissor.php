<?php

namespace app\models\batch;

use app\batch\Model;
use app\models\BairroQuarteirao;
use app\models\Bairro;
use app\models\EspecieTransmissor;
use app\models\DepositoTipo;

class FocosTransmissor extends Model
{
    /**
     * @inheritdoc
     */
    public function columnLabels()
    {
        return [
            'laboratorio' => 'Laboratório',
            'tecnico' => 'Técnico',
            'tipo_deposito' => 'Sigla do Tipo de Depósito',
            'especie' => 'Espécie Transmissor',
            'bairro' => 'Bairro',
            'quarteirao' => 'Quarteirão',
            'data_entrada' => 'Data da Entrada',
            'data_exame' => 'Data do Exame',
            'data_coleta' => 'Data da Coleta',
            'qtde_aquatica' => 'Qtde. Forma Aquática',
            'qtde_adulta' => 'Qtde. Forma Adulta',
            'qtde_ovos' => 'Qtde. Ovos',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function columnHints()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function insert($row)
    {
        $bairro = Bairro::find()->doNome($row->getValue('bairro'))->one();
        if(!$bairro) {
            $row->addError('Bairro não localizado');
            return false;
        }
        
        $bairroQuarteirao = BairroQuarteirao::find()->doBairro($bairro->id)->doNumero($row->getValue('quarteirao'))->one();
        if(!$bairroQuarteirao) {
            $row->addError('Quarteirão não localizado');
            return false;
        }
        
        $tipoDeposito = DepositoTipo::find()->daSigla($row->getValue('tipo_deposito'))->one();
        if(!$tipoDeposito) {
            $row->addError('Tipo de depósito não localizado');
            return false;
        }
        
        $especieTransmissor = EspecieTransmissor::find()->doNome($row->getValue('especie'))->one();
        if(!$especieTransmissor) {
            $row->addError('Espécie transmissor não localizado');
            return false;
        }
        
        $foco = new \app\models\FocoTransmissor;
        $foco->inserido_por = \Yii::$app->user->identity->id;
        $foco->tipo_deposito_id = $tipoDeposito->id;
        $foco->especie_transmissor_id = $especieTransmissor->id;
        $foco->bairro_quarteirao_id = $bairroQuarteirao->id;
        $foco->data_coleta = $row->getValue('data_coleta');
        $foco->data_entrada = $row->getValue('data_entrada');
        $foco->data_exame = $row->getValue('data_exame');
        $foco->quantidade_ovos = $row->getValue('qtde_ovos');
        $foco->quantidade_forma_adulta = $row->getValue('qtde_adulta');
        $foco->quantidade_forma_aquatica = $row->getValue('qtde_aquatica');
        $foco->laboratorio = $row->getValue('laboratorio');
        $foco->tecnico = $row->getValue('tecnico');
        
        return $foco->save();
    }
}
