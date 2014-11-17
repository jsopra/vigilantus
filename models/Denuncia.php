<?php

namespace app\models;
use app\components\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Este é a classe de modelo da tabela "denuncias".
 *
 * Estas são as colunas disponíveis na tabela "denuncias":
 * @property integer $id
 * @property string $data_criacao
 * @property integer $municipio_id
 * @property string $nome
 * @property string $telefone
 * @property integer $bairro_id
 * @property string $endereco
 * @property integer $imovel_id
 * @property string $email
 * @property string $pontos_referencia
 * @property string $mensagem
 * @property string $anexo
 * @property integer $tipo_imovel
 * @property integer $localizacao
 * @property integer $status
 * @property string $nome_original_anexo
 * @property integer $denuncia_tipo_problema_id
 * @property integer $bairro_quarteirao_id
 *
 * @property DenunciaHistorico[] $denunciaHistoricos
 * @property Municipios $municipio
 * @property Bairros $bairro
 * @property Imoveis $imovel
 */
class Denuncia extends ActiveRecord 
{
	public $file;
	public $usuario_id;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'denuncias';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['data_criacao'], 'safe'],
			[['municipio_id', 'bairro_id', 'endereco', 'mensagem', 'tipo_imovel'], 'required'],
			[['municipio_id', 'bairro_id', 'imovel_id', 'tipo_imovel', 'localizacao', 'status', 'denuncia_tipo_problema_id', 'usuario_id', 'bairro_quarteirao_id'], 'integer'],
			[['nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo'], 'string'],
			['status', 'default', 'value' => DenunciaStatus::AVALIACAO],
			['status', 'in', 'range' => DenunciaStatus::getIDs()],
			['localizacao', 'in', 'range' => DenunciaTipoImovel::getIDs()],
			['tipo_imovel', 'in', 'range' => DenunciaTipoLocalizacao::getIDs()],
			[['bairro_quarteirao_id', 'denuncia_tipo_problema_id'], 'required', 'on' => ['aprovacao']],
			[['file'], 'file'],
			[['email'], 'email'],
			['usuario_id', 'required', 'on' => ['aprovacao', 'trocaStatus']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data_criacao' => 'Data Criação',
			'municipio_id' => 'Município',
			'nome' => 'Nome',
			'telefone' => 'Telefone',
			'bairro_id' => 'Bairro',
			'endereco' => 'Endereco',
			'imovel_id' => 'Imóvel',
			'email' => 'Email',
			'pontos_referencia' => 'Pontos de Referência',
			'mensagem' => 'Mensagem',
			'anexo' => 'Anexo',
			'tipo_imovel' => 'Tipo do Imovel',
			'localizacao' => 'Localização',
			'status' => 'Status',
			'file' => 'Anexo',
			'nome_original_anexo' => 'Nome Original do Anexo',
			'denuncia_tipo_problema_id' => 'Tipo do Problema',
			'bairro_quarteirao_id' => 'Quarteirão',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDenunciaHistoricos()
	{
		return $this->hasMany(DenunciaHistorico::className(), ['denuncia_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairro()
	{
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovel()
	{
		return $this->hasOne(Imovel::className(), ['id' => 'imovel_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDenunciaTipoProblema()
	{
		return $this->hasOne(DenunciaTipoProblema::className(), ['id' => 'denuncia_tipo_problema_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
	}

	/**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = NULL) {

        $currentTransaction = $this->getDb()->getTransaction();		
		$newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();
        
        try {
            
        	$oldStatus = isset($this->oldAttributes['status']) ? $this->oldAttributes['status'] : null;
        	$isNewRecord = $this->isNewRecord;

            $result = parent::save($runValidation, $attributes);
            
            if ($result) {

               
            	$salvouHistorico = true;

            	if($isNewRecord) {

            		$historico = new DenunciaHistorico;
            		$historico->municipio_id = $this->municipio_id;
            		$historico->denuncia_id = $this->id;
            		$historico->tipo = DenunciaHistoricoTipo::INCLUSAO;
            		$historico->status_novo = DenunciaStatus::AVALIACAO;
            		
            		$salvouHistorico = $historico->save();
            	}
            	else {

            		if($oldStatus != $this->status) {

            			$historico = new DenunciaHistorico;
	            		$historico->municipio_id = $this->municipio_id;
	            		$historico->denuncia_id = $this->id;
	            		$historico->tipo = DenunciaHistoricoTipo::REPROVACAO;
	            		$historico->status_antigo = $oldStatus;
	            		$historico->status_novo = $this->status;
	            		$historico->usuario_id = $this->usuario_id;
	            		
	            		$salvouHistorico = $historico->save();

	            		if($salvouHistorico && $this->email) {
                			\app\models\redis\Queue::push('AlertaAlteracaoStatusDenunciaJob', ['id' => $this->id]); 
	            		}
            		}

            	}

                if($salvouHistorico) {

                    if($newTransaction) {
                        $newTransaction->commit();
                    }
                }
                else {
                    if($newTransaction) {
                        $newTransaction->rollback();
                    }
                    
                    $result = false;                    
                }
            } 
            else {
                if($newTransaction) {
                    $newTransaction->rollback();
                }
            }
        } 
        catch (\Exception $e) {
            if($newTransaction) {
                $newTransaction->rollback();
            }
            throw $e;
        }

        return $result;
    }
}
