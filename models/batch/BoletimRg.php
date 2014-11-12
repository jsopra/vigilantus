<?php

namespace app\models\batch;

use app\batch\Model;
use app\models\ImovelTipo;
use app\models\BoletimRgFechamento;
use app\models\BairroQuarteirao;
use app\models\Bairro;

class BoletimRg extends Model
{
    /**
     * @inheritdoc
     */
    public function columnLabels()
    {
        $labels = [
            'bairro' => 'Nome do Bairro',
            'quarteirao' => 'Número do Quarteirão',
            'sequencia' => 'Sequêcia',
            'data' => 'Data da Coleta',
        ];
        
        $tipoImovel = ImovelTipo::find()->orderBy('id')->all();
        
        foreach($tipoImovel as $tipo) {
            $labels['imovelTipo_' . $tipo->id] = $tipo->nome;
        }
        
        foreach($tipoImovel as $tipo) {
            $labels['imovelTipo_' . $tipo->id . '_lira'] = $tipo->nome . ' Lira';
        }
        
        return $labels;
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
    public function insert($row, $userId = null, $municipioId = null)
    {
        $bairro = Bairro::find()->doNome($row->getValue('bairro'))->one();
        if(!$bairro) {
            $row->addError('Bairro não localizado');
            return false;
        }
        
        $numero = $row->getValue('quarteirao') . ($row->getValue('sequencia') ? ('-' . $row->getValue('sequencia')) : ''); 
        $bairroQuarteirao = BairroQuarteirao::find()->doBairro($bairro->id)->doNumero($numero)->one();
        
        if(!$bairroQuarteirao) {
            $row->addError('Quarteirão não localizado');
            return false;
        }
        
        $transaction = \Yii::$app->db->beginTransaction();
        
        $boletimRg = \app\models\BoletimRg::find()
            ->doBairro($bairro->id)
            ->doBairroQuarteirao($bairroQuarteirao->id)
            ->daData($row->getValue('data'))
            ->one();
        
        if($boletimRg) {
            $boletimRg->delete();
        }
        
        $boletimRg = new \app\models\BoletimRg;
        $boletimRg->bairro_id = $bairro->id;
        $boletimRg->bairro_quarteirao_id = $bairroQuarteirao->id;
        $boletimRg->data = $row->getValue('data');
        $boletimRg->inserido_por = $userId ? $userId : \Yii::$app->user->identity->id;

        if($municipioId) {
            $boletimRg->municipio_id = $municipioId;
        }

        if(!$boletimRg->save()) {
            $row->addErrorsFromObject($boletimRg);
            $transaction->rollback();
            return false;
        }

        $tipoImovel = ImovelTipo::find()->orderBy('id')->all();
        foreach($tipoImovel as $tipo) {
            
            $valor = $row->getValue('imovelTipo_' . $tipo->id) ? $row->getValue('imovelTipo_' . $tipo->id) : 0;

            $fechamento = $this->_addFechamento($boletimRg, $tipo->id, $valor, false);
            if(!$fechamento->save()) {
                $row->addErrorsFromObject($fechamento);
                $transaction->rollback();
                return false;
            }
            
            $valor = $row->getValue('imovelTipo_' . $tipo->id . '_lira') ? $row->getValue('imovelTipo_' . $tipo->id . '_lira') : 0;
            $fechamento = $this->_addFechamento($boletimRg, $tipo->id, $valor, true);  
            if(!$fechamento->save()) {
                $row->addErrorsFromObject($fechamento);
                $transaction->rollback();
                return false;
            }
        }

        $transaction->commit();
        
        return true;
    }
    
    /**
     * Instancia um objeto BoletimFechamento sem salvá-lo
     * @param BoletimRg $boletim
     * @param type $imovelTipo
     * @param type $quantidade
     * @param type $lira
     * @return BoletimRgFechamento 
     */
    private function _addFechamento(\app\models\BoletimRg $boletim, $imovelTipo, $quantidade, $lira) {

        $boletimFechamento = new BoletimRgFechamento;
        
        $boletimFechamento->municipio_id = $boletim->municipio_id;
        $boletimFechamento->boletim_rg_id = $boletim->id;
        $boletimFechamento->imovel_lira = $lira;
        $boletimFechamento->imovel_tipo_id = $imovelTipo;
        $boletimFechamento->quantidade = $quantidade;   
        
        return $boletimFechamento;
    }
}
