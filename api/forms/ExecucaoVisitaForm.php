<?php

namespace api\forms;

use Yii;
use yii\base\Model;
use app\models\SemanaEpidemiologicaVisita;
use app\models\VisitaImovel;
use app\models\VisitaImovelDeposito;

class ExecucaoVisitaForm extends Model
{
    public $visita_id;
    public $visita_status_id;
    public $usuario_id;
    public $imoveis;
    public $data_atividade;

    private $visita;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['visita_id', 'usuario_id', 'visita_status_id', 'data_atividade'], 'required'],
            [['visita_id', 'usuario_id', 'visita_status_id'], 'integer'],
            [['data_atividade', 'imoveis', 'visita'], 'safe'],
        ];
    }

    public function afterValidate()
    {
        $parent = parent::afterValidate();

        if (!$this->imoveis) {
            $this->addError('imoveis', 'Ao menos um imóvel deve ser enviado para executar a visita');
        }  

        $this->visita = SemanaEpidemiologicaVisita::find()->andWhere(['id' => $this->visita_id])->one();
        if(!$this->visita) {
            $this->addError('visita_id', 'Visita não localizada');
        }

        return $parent;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'visita_id' => 'Visita',
            'usuario_id' => 'Usuário',
            'visita_status_id' => 'Status da Visita',
            'data_atividade' => 'Data da Atividade',
            'imoveis' => 'Imóveis',
        ];
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            //atualiza visita
            $this->visita->visita_status_id = $this->visita_status_id;
            $this->visita->atualizado_por = $this->usuario_id;
            if (!$this->visita->save()) {
                $this->addError('visita_id', 'Erro ao atualizar status da visita');
                throw new \Exception('Erro ao atualizar status da visita');
            }

            //adiciona imóveis da visita
            foreach ($this->imoveis as $imovel)
            {
                $visitaImovel = new VisitaImovel;
                $visitaImovel->inserido_por = $this->usuario_id;
                $visitaImovel->semana_epidemiologica_visita_id = $this->visita->id;
                $visitaImovel->visita_atividade_id = $this->getValue($imovel, 'visita_atividade_id');
                $visitaImovel->quarteirao_id = $this->getValue($imovel, 'quarteirao_id');
                $visitaImovel->logradouro = $this->getValue($imovel, 'logradouro');
                $visitaImovel->numero = $this->getValue($imovel, 'numero');
                $visitaImovel->sequencia = $this->getValue($imovel, 'sequencia');
                $visitaImovel->complemento = $this->getValue($imovel, 'complemento');
                $visitaImovel->tipo_imovel_id = $this->getValue($imovel, 'tipo_imovel_id');
                $visitaImovel->hora_entrada = $this->getValue($imovel, 'hora_entrada');
                $visitaImovel->visita_tipo = $this->getValue($imovel, 'visita_tipo');
                $visitaImovel->pendencia = $this->getValue($imovel, 'pendencia');
                $visitaImovel->depositos_eliminados = $this->getValue($imovel, 'depositos_eliminados');
                $visitaImovel->numero_amostra_inicial = $this->getValue($imovel, 'numero_amostra_inicial');
                $visitaImovel->numero_amostra_final = $this->getValue($imovel, 'numero_amostra_final');
                $visitaImovel->quantidade_tubitos = $this->getValue($imovel, 'quantidade_tubitos');
                $visitaImovel->focal_imovel_tratamento = $this->getValue($imovel, 'focal_imovel_tratamento');
                $visitaImovel->focal_larvicida_tipo = $this->getValue($imovel, 'focal_larvicida_tipo');
                $visitaImovel->focal_larvicida_qtde_gramas = $this->getValue($imovel, 'focal_larvicida_qtde_gramas');
                $visitaImovel->focal_larvicida_qtde_dep_tratado = $this->getValue($imovel, 'focal_larvicida_qtde_dep_tratado');
                $visitaImovel->perifocal_adulticida_tipo = $this->getValue($imovel, 'perifocal_adulticida_tipo');
                $visitaImovel->perifocal_adulticida_qtde_cargas = $this->getValue($imovel, 'perifocal_adulticida_qtde_cargas');

                if (!$visitaImovel->save()) {
                    $this->addError('imoveis', 'Erro ao salvar imóvel em visita: ' . print_r($visitaImovel->errors, true));
                    throw new \Exception('Erro ao salvar imóvel em visita');
                }

                $depositos = isset($imovel['depositos']) && is_array($imovel['depositos']);
                if ($depositos) {
                    foreach ($depositos as $deposito) {
                        $visitaDeposito = new VisitaImovelDeposito;
                        $visitaDeposito->visita_id = $this->visita->id;
                        $visitaDeposito->tipo_deposito_id = $this->getValue($deposito, 'tipo_deposito_id');
                        $visitaDeposito->quantidade = $this->getValue($deposito, 'quantidade');
                    }

                    if (!$visitaDeposito->save()) {
                        $this->addError('imoveis', 'Erro ao salvar depósito de imóvel em visita: ' . print_r($visitaDeposito->errors, true));
                        throw new \Exception('Erro ao salvar depósito de imóvel em visita');
                    }
                }
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            die(var_dump($e));
            return false;
        }
    }

    private function getValue($array, $index)
    {
        return isset($array[$index]) ? $array[$index] : null;
    }
}
