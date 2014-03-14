<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "deposito_tipos".
 *
 * Estas são as colunas disponíveis na tabela "deposito_tipos":
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $deposito_tipo_pai
 * @property string $descricao
 * @property string $sigla
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 *
 * @property Municipios $municipio
 * @property DepositoTipos $depositoTipoPai
 */
class DepositoTipo extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'deposito_tipos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['municipio_id', 'descricao', 'sigla'], 'required'],
			[['municipio_id', 'deposito_tipo_pai', 'inserido_por', 'atualizado_por'], 'integer'],
			[['descricao', 'sigla', 'data_cadastro', 'data_atualizacao'], 'string'],
            ['inserido_por', 'required', 'on' => 'insert'],
            ['atualizado_por', 'required', 'on' => 'update'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Municipio ID',
			'deposito_tipo_pai' => 'Deposito Tipo Pai',
			'descricao' => 'Descricao',
			'sigla' => 'Sigla',
            'data_cadastro' => 'Data de Cadastro',
            'data_atualizacao' => 'Data de Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipios::className(), ['id' => 'municipio_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDepositoTipoPai()
	{
		return $this->hasOne(DepositoTipo::className(), ['id' => 'deposito_tipo_pai']);
	}
    
    /**
     * Salva atributos do objeto
     * @return boolean
     * @throws CException se o registro for novo
     */
    public function save($runValidation = true, $attributes = null) {

        $transaction = $this->getTransaction();
        
        try {
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {
                $transaction->commit();
            } 
            else {
                $transaction->rollback();
            }
        } 
        catch (\Exception $e) {
            $transaction->rollback();
                
            throw $e;
        }

        return $result;
    }
    
    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        $transaction = $this->getTransaction();
        
        try {
            
            $return = parent::delete();
            
            if ($result) {
                $transaction->commit();
            } 
            else {
                $transaction->rollback();
            }
        } 
        catch (\Exception $e) {

            $transaction->rollback();
                
            throw $e;
        }

        return $result;
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
     * Retorna uma conexão
     */
    private function getTransaction()
    {
        $currentTransaction = $this->getDb()->getTransaction();     
        return $currentTransaction ? $currentTransaction : $this->getDb()->beginTransaction();
    }

}
