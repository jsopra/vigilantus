<?php

namespace app\models;

use app\components\ClienteActiveRecord;
use Hashids\Hashids;
use yii\web\UploadedFile;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "denuncias".
 *
 * Estas são as colunas disponíveis na tabela "denuncias":
 * @property integer $id
 * @property string $data_criacao
 * @property integer $cliente_id
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
 * @property string $hash_acesso_publico
 * @property string $data_fechamento
 *
 * @property DenunciaHistorico[] $denunciaHistoricos
 * @property Cliente $cliente
 * @property Bairros $bairro
 * @property Imoveis $imovel
 */
class Denuncia extends ClienteActiveRecord
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
			[['data_criacao', 'data_fechamento', 'telefone'], 'safe'],
			[['cliente_id', 'bairro_id', 'endereco', 'mensagem', 'tipo_imovel'], 'required'],
			[['cliente_id', 'bairro_id', 'imovel_id', 'tipo_imovel', 'localizacao', 'status', 'denuncia_tipo_problema_id', 'usuario_id', 'bairro_quarteirao_id'], 'integer'],
            ['hash_acesso_publico', 'unique', 'when' => function($model, $attribute) {
                return !empty($this->hash_acesso_publico);
            }],
			[['nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo'], 'string'],
			['status', 'default', 'value' => DenunciaStatus::AVALIACAO],
			['status', 'in', 'range' => DenunciaStatus::getIDs()],
			['localizacao', 'in', 'range' => DenunciaTipoImovel::getIDs()],
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
			'cliente_id' => 'Município Cliente',
			'nome' => 'Nome',
			'telefone' => 'Telefone',
			'bairro_id' => 'Bairro',
			'endereco' => 'Endereço',
			'imovel_id' => 'Imóvel',
			'email' => 'Email',
			'pontos_referencia' => 'Pontos de Referência',
			'mensagem' => 'Mensagem',
			'anexo' => 'Anexo',
			'tipo_imovel' => 'Tipo do Imóvel',
			'localizacao' => 'Localização',
			'status' => 'Status',
			'file' => 'Anexo',
			'nome_original_anexo' => 'Nome Original do Anexo',
			'denuncia_tipo_problema_id' => 'Tipo do Problema',
			'bairro_quarteirao_id' => 'Quarteirão',
            'hash_acesso_publico' => 'Protocolo',
            'data_fechamento' => 'Data de Fechamento',
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
	public function getCliente()
	{
		return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
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
    public function save($runValidation = true, $attributes = NULL)
    {
        $transaction = $this->getDb()->beginTransaction();

        try {

        	$oldStatus = isset($this->oldAttributes['status']) ? $this->oldAttributes['status'] : null;
        	$isNewRecord = $this->isNewRecord;

            if(!$this->isNewRecord && $oldStatus != $this->status && in_array($this->status, DenunciaStatus::getStatusTerminativos())) {
                $this->data_fechamento = new Expression('NOW()');
            }

            $result = parent::save($runValidation, $attributes);

            if ($result) {

            	$salvouHistorico = true;

            	if($isNewRecord) {

                    $this->hash_acesso_publico = $this->_createHashAcessoPublico();
                    $this->update(false, ['hash_acesso_publico']);

            		$historico = new DenunciaHistorico;
            		$historico->cliente_id = $this->cliente_id;
            		$historico->denuncia_id = $this->id;
            		$historico->tipo = DenunciaHistoricoTipo::INCLUSAO;
            		$historico->status_novo = DenunciaStatus::AVALIACAO;

            		$salvouHistorico = $historico->save();
            	}
            	else {

            		if($oldStatus != $this->status) {

            			$historico = new DenunciaHistorico;
	            		$historico->cliente_id = $this->cliente_id;
	            		$historico->denuncia_id = $this->id;
	            		$historico->tipo = $this->status == DenunciaStatus::REPROVADA ? DenunciaHistoricoTipo::REPROVACAO : DenunciaHistoricoTipo::INFORMACAO;
	            		$historico->status_antigo = $oldStatus;
	            		$historico->status_novo = $this->status;
	            		$historico->usuario_id = $this->usuario_id;

	            		$salvouHistorico = $historico->save();

	            		if($salvouHistorico && $this->email) {
                            \perspectivain\gearman\BackgroundJob::register(
                                'AlertaAlteracaoStatusDenunciaJob',
                                [
                                    'id' => $this->id,
                                    'key' => getenv('GEARMAN_JOB_KEY')
                                ],
                                \perspectivain\gearman\BackgroundJob::NORMAL,
                                \Yii::$app->params['gearmanQueueName']
                            );
	            		}
            		}

            	}

                if($salvouHistorico) {
                    $transaction->commit();
                }
                else {
                    $transaction->rollback();
                    $result = false;
                }
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
     * @return string
     */
    public function getProtocolo()
    {
        return $this->hash_acesso_publico;
    }

    /**
     * @return int
     */
    public function getQtde_dias_em_aberto()
    {
        $dataCriacao = new \DateTime($this->data_criacao);
        $dataFechamento = $this->data_fechamento ? new \DateTime($this->data_fechamento) : new \DateTime();

        return $dataFechamento->diff($dataCriacao)->days;
    }

    /**
     * @return int
     */
    public function getQuantidadeAveriguacoes()
    {
        return DenunciaHistorico::find()->where(['denuncia_id' => $this->id, 'tipo' => DenunciaHistoricoTipo::AVERIGUACAO])->count();
    }

    /**
     * @return string
     */
    private function _createHashAcessoPublico()
    {
        $sal = '';
        $tamanhoMinimo = 4;
        $alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $hashids = new Hashids($sal, $tamanhoMinimo, $alfabeto);

        return $hashids->encode($this->id);
    }
}
