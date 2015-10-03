<?php

namespace app\models;

use app\components\ClienteActiveRecord;
use Hashids\Hashids;
use perspectivain\gearman\BackgroundJob;
use perspectivain\postgis\PostgisTrait;
use Yii;
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
    use PostgisTrait;

    const SCENARIO_CARGA = 'carga';
    const SCENARIO_TROCA_STATUS = 'trocaStatus';
    const SCENARIO_INSERCAO = 'insert';
    const SCENARIO_APROVACAO = 'aprovacao';

	public $file;
	public $usuario_id;
    public $observacoes;

    /**
     * @var array cache das coordenadas convertidas para um array (lat, long).
     */
    protected $array_coordenadas;

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
            $statusMudou = $oldStatus != $this->status;

            if (!$isNewRecord && $statusMudou && in_array($this->status, OcorrenciaStatus::getStatusTerminativos())) {
                $this->data_fechamento = new Expression('NOW()');
            }

            if (!empty($this->ocorrencia_tipo_problema_id)) {
                $this->descricao_outro_tipo_problema = null;
            }

            if (!parent::save($runValidation, $attributes)) {
                $transaction->rollback();
                return false;
            }

            $historico = new OcorrenciaHistorico;
            $historico->cliente_id = $this->cliente_id;
            $historico->usuario_id = $this->usuario_id;
            $historico->ocorrencia_id = $this->id;

        	if ($isNewRecord) {
                $historico->tipo = OcorrenciaHistoricoTipo::INCLUSAO;
                $historico->status_novo = OcorrenciaStatus::AVALIACAO;

                $this->hash_acesso_publico = $this->_createHashAcessoPublico();
                $this->update(false, ['hash_acesso_publico']);

        	} elseif ($statusMudou) {
        		$historico->tipo = OcorrenciaHistoricoTipo::INFORMACAO;
        		$historico->status_antigo = $oldStatus;
        		$historico->status_novo = $this->status;
                $historico->observacoes = $this->observacoes ?: null;

                if ($this->status == OcorrenciaStatus::REPROVADA) {
                    $historico->tipo = OcorrenciaHistoricoTipo::REPROVACAO;
                } elseif ($this->status == OcorrenciaStatus::APROVADA) {
                    $historico->tipo = OcorrenciaHistoricoTipo::APROVACAO;
                }
        	}

            if ($historico->save()) {
                if (($isNewRecord || $statusMudou) && $this->email) {
                    BackgroundJob::register(
                        'AlertaAlteracaoStatusOcorrenciaJob',
                        [
                            'id' => $this->id,
                            'isNewRecord' => $isNewRecord,
                            'key' => getenv('GEARMAN_JOB_KEY')
                        ],
                        BackgroundJob::NORMAL,
                        Yii::$app->params['gearmanQueueName']
                    );
                }

                $transaction->commit();
                return true;
            }
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        $transaction->rollback();
        return false;
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

    /**
     * @return float|null
     */
    public function getLatitude()
    {
        if ($coordenadas = $this->getArrayCoordenadas()) {
            return $coordenadas[1];
        }
    }

    /**
     * @return float|null
     */
    public function getLongitude()
    {
        if ($coordenadas = $this->getArrayCoordenadas()) {
            return $coordenadas[0];
        }
    }

    /**
     * Converte coordenadas do Postgres
     * @return array|false
     */
    protected function getArrayCoordenadas()
    {
        if (null === $this->array_coordenadas) {
            $this->array_coordenadas = false;

            $coordenadas = $this->wktToArray('Point', 'coordenadas');
            if (is_array($coordenadas) && count($coordenadas) == 2) {
                $this->array_coordenadas = $coordenadas;
            }
        }

        return $this->array_coordenadas;
    }
}
