<?php

namespace app\models;

use app\components\ClienteActiveRecord;
use Hashids\Hashids;
use yii\web\UploadedFile;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "ocorrencias".
 *
 * Estas são as colunas disponíveis na tabela "ocorrencias":
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
 * @property integer $ocorrencia_tipo_problema_id
 * @property integer $bairro_quarteirao_id
 * @property string $hash_acesso_publico
 * @property string $data_fechamento
 * @property string $numero_controle
 * @property string $coordenadas
 * @property string $descricao_outro_tipo_problema
 *
 * @property OcorrenciaHistorico[] $ocorrenciaHistoricos
 * @property Cliente $cliente
 * @property Bairros $bairro
 * @property Imoveis $imovel
 */
class Ocorrencia extends ClienteActiveRecord
{
    const SCENARIO_CARGA = 'carga';
    const SCENARIO_TROCA_STATUS = 'trocaStatus';
    const SCENARIO_INSERCAO = 'insert';
    const SCENARIO_APROVACAO = 'aprovacao';

	public $file;
	public $usuario_id;
    public $observacoes;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ocorrencias';
	}

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CARGA => ['carga'],
            self::SCENARIO_TROCA_STATUS => ['trocaStatus'],
            self::SCENARIO_APROVACAO => ['aprovacao'],
        ]);
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['data_criacao', 'data_fechamento', 'telefone', 'numero_controle', 'coordenadas'], 'safe'],
			[['cliente_id', 'endereco', 'mensagem'], 'required'],
            [['tipo_imovel', 'bairro_id'], 'required', 'on' => 'insert'],
			[['cliente_id', 'bairro_id', 'imovel_id', 'tipo_imovel', 'localizacao', 'status', 'ocorrencia_tipo_problema_id', 'usuario_id', 'bairro_quarteirao_id'], 'integer'],
            ['hash_acesso_publico', 'unique', 'when' => function($model, $attribute) {
                return !empty($this->hash_acesso_publico);
            }],
			[['nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo', 'observacoes'], 'string'],
			['status', 'default', 'value' => OcorrenciaStatus::AVALIACAO],
			['status', 'in', 'range' => OcorrenciaStatus::getIDs()],
			['localizacao', 'in', 'range' => OcorrenciaTipoImovel::getIDs()],
			[['file'], 'file'],
			[['email'], 'email'],
			['usuario_id', 'required', 'on' => ['aprovacao', 'trocaStatus']],
            [
                'ocorrencia_tipo_problema_id',
                'exist',
                'skipOnEmpty' => true,
                'targetClass' => OcorrenciaTipoProblema::className(),
                'targetAttribute' => 'id'
            ],
            [
                'descricao_outro_tipo_problema',
                'required',
                'when' => function ($model) {
                    return is_null($model->ocorrencia_tipo_problema_id);
                },
                'whenClient' => "function(attribute, value) {
                    return \$('#ocorrencia-ocorrencia_tipo_problema_id').val() === '';
                }",
                'skipOnError' => true,
            ],
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
			'status' => 'Situação',
			'file' => 'Anexo',
			'nome_original_anexo' => 'Nome Original do Anexo',
			'ocorrencia_tipo_problema_id' => 'Tipo do Problema',
			'bairro_quarteirao_id' => 'Quarteirão',
            'hash_acesso_publico' => 'Protocolo',
            'data_fechamento' => 'Data de Fechamento',
            'numero_controle' => 'Nº Controle',
            'observacoes' => 'Observações',
            'coordenadas' => 'Coordenadas',
            'descricao_outro_tipo_problema' => 'Descrição do Problema',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getOcorrenciaHistoricos()
	{
		return $this->hasMany(OcorrenciaHistorico::className(), ['ocorrencia_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getHistoricoRejeicao()
    {
        return $this->hasOne(OcorrenciaHistorico::className(), ['ocorrencia_id' => 'id'])
            ->andWhere(['status_novo' => OcorrenciaStatus::REPROVADA]);
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
	public function getOcorrenciaTipoProblema()
	{
		return $this->hasOne(OcorrenciaTipoProblema::className(), ['id' => 'ocorrencia_tipo_problema_id']);
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

            if(!$this->isNewRecord && $oldStatus != $this->status && in_array($this->status, OcorrenciaStatus::getStatusTerminativos())) {
                $this->data_fechamento = new Expression('NOW()');
            }

            if (!empty($this->ocorrencia_tipo_problema_id)) {
                $this->descricao_outro_tipo_problema = null;
            }

            $result = parent::save($runValidation, $attributes);

            if ($result) {

            	$salvouHistorico = true;

            	if($isNewRecord) {

                    $this->hash_acesso_publico = $this->_createHashAcessoPublico();
                    $this->update(false, ['hash_acesso_publico']);

            		$historico = new OcorrenciaHistorico;
            		$historico->cliente_id = $this->cliente_id;
            		$historico->ocorrencia_id = $this->id;
            		$historico->tipo = OcorrenciaHistoricoTipo::INCLUSAO;
            		$historico->status_novo = OcorrenciaStatus::AVALIACAO;

            		$salvouHistorico = $historico->save();
            	}
            	else {

            		if ($oldStatus != $this->status) {

            			$historico = new OcorrenciaHistorico;
	            		$historico->cliente_id = $this->cliente_id;
	            		$historico->ocorrencia_id = $this->id;
	            		$historico->tipo = $this->status == OcorrenciaStatus::REPROVADA ? OcorrenciaHistoricoTipo::REPROVACAO : OcorrenciaHistoricoTipo::INFORMACAO;
	            		$historico->status_antigo = $oldStatus;
	            		$historico->status_novo = $this->status;
	            		$historico->usuario_id = $this->usuario_id;

                        if ($this->observacoes) {
                            $historico->observacoes = $this->observacoes;
                        }

	            		$salvouHistorico = $historico->save();

	            		if ($salvouHistorico && $this->email) {
                            \perspectivain\gearman\BackgroundJob::register(
                                'AlertaAlteracaoStatusOcorrenciaJob',
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
                } else {
                    $transaction->rollback();
                    $result = false;
                }
            } else {
                $transaction->rollback();
                $result = false;
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
        return OcorrenciaHistorico::find()->where(['ocorrencia_id' => $this->id, 'tipo' => OcorrenciaHistoricoTipo::AVERIGUACAO])->count();
    }

    /**
     * @return string
     */
    public function getDescricaoTipoProblema()
    {
        if ($tipoProblema = $this->ocorrenciaTipoProblema) {
            return $tipoProblema->nome;
        }
        return $this->descricao_outro_tipo_problema;
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
