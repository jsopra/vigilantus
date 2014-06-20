<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "focos_transmissores".
 *
 * Estas são as colunas disponíveis na tabela "focos_transmissores":
 * @property integer $id
 * @property integer $inserido_por
 * @property integer $atualizado_por
 * @property integer $tipo_deposito_id
 * @property integer $especie_transmissor_id
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property string $data_entrada
 * @property string $data_exame
 * @property string $data_coleta
 * @property integer $quantidade_forma_aquatica
 * @property integer $quantidade_forma_adulta
 * @property integer $quantidade_ovos
 * @property string $laboratorio
 * @property string $tecnico
 * @property integer $imovel_id
 *
 * @property Usuario $inseridoPor
 * @property Usuario $atualizadoPor
 * @property DepositoTipo $tipoDeposito
 * @property EspecieTransmissor $especieTransmissor
 */
class FocoTransmissor extends ActiveRecord 
{
    public $bairro_id;
    public $foco_ativo;
    public $imovel_lira;
    
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
            [['inserido_por', 'tipo_deposito_id', 'especie_transmissor_id', 'imovel_id'], 'required'],
            [['quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'], 'integer', 'min' => 0],
            [['!inserido_por', '!atualizado_por'], 'exist', 'targetClass' => Usuario::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['tipo_deposito_id', 'exist', 'targetClass' => DepositoTipo::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['imovel_id', 'exist', 'targetClass' => Imovel::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['!data_cadastro', '!data_atualizacao', 'data_entrada', 'data_exame', 'data_coleta'], 'date'],
            [['laboratorio', 'tecnico'], 'string', 'max' => 256]
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
            'tipo_deposito_id' => 'Tipo de Depósito',
            'especie_transmissor_id' => 'Espécie de Transmissor',
            'data_cadastro' => 'Data de Cadastro',
            'data_atualizacao' => 'Data de Atualização',
            'data_entrada' => 'Data da Entrada',
            'data_exame' => 'Data do Exame',
            'data_coleta' => 'Data da Coleta',
            'quantidade_forma_aquatica' => 'Qtde. Forma Aquática',
            'quantidade_forma_adulta' => 'Qtde. Forma Adulta',
            'quantidade_ovos' => 'Qtde. Ovos',
            'laboratorio' => 'Laboratório',
            'tecnico' => 'Técnico',
            'imovel_id' => 'Endereço do Imóvel',
            'bairro_id' => 'Bairro',
            'foco_ativo' => 'Foco Ativo',
            'imovel_lira' => 'Imóvel LIRA',
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
    
    /**
     * @return \yii\db\ActiveRelation
     */
    public function getImovel() 
    {
        return $this->hasOne(Imovel::className(), ['id' => 'imovel_id']);
    }
    
    /**
     * Verifica se um foco ainda é ativo conforme configuração de dias do projeto
     * @return boolean 
     */
    public function isAtivo() {
        
        list($anoColeta, $mesColeta, $diaColeta) = explode('-', $this->data_coleta);
        $dataColeta = new \DateTime();
        $dataColeta->setDate($anoColeta, $mesColeta, $diaColeta);
        
        $dataValidade = new \DateTime();
        $dataValidade->modify('-' . $this->especieTransmissor->qtde_dias_permanencia_foco . ' days');
        
        $validacaoData = $dataColeta >= $dataValidade;
        
        if(!$validacaoData)
            return false;
        
        return $this->quantidade_forma_adulta > 0 || $this->quantidade_forma_aquatica > 0 || $this->quantidade_ovos > 0;
    }
}
