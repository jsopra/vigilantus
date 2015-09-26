<?php
namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Ocorrencia;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaTipoImovel;
use app\models\OcorrenciaTipoProblema;
use app\helpers\models\OcorrenciaHelper;
use perspectivain\postgis\PostgisTrait as postgisTrait;

class OcorrenciaForm extends Model
{
    use postgisTrait;

    const SCENARIO_WIZARD_LOCAL = 'wizard-local';
    const SCENARIO_WIZARD_DETALHES = 'wizard-detalhes';
    const SCENARIO_WIZARD_IDENTIFICACAO = 'wizard-identificacao';

    /**
     * atributos DE/PARA com Ocorrência
     * @var array
     */
    private $ocorrenciaFields = [
        'nome',
        'telefone',
        'bairro_id',
        'endereco',
        'email',
        'pontos_referencia',
        'mensagem',
        'anexo',
        'tipo_imovel',
        'coordenadas',
        'file',
        'nome_original_anexo',
        'ocorrencia_tipo_problema_id',
        'mensagem',
        'cliente_id',
        'descricao_outro_tipo_problema',
    ];

    /**
     * @var string
     */
    private $session_name = 'form-ocorrencia';

    public $cliente_id;
    public $coordenadasJson;

    //w1
    public $ocorrencia_tipo_problema_id;
    public $descricao_outro_tipo_problema;
    public $tipo_imovel;
    public $bairro_id;
    public $endereco;
    public $pontos_referencia;
    public $coordenadas;

    //w2
    public $file;
    public $anexo;
    public $nome_original_anexo;
    public $mensagem;

    //w3
    public $nome;
    public $telefone;
    public $email;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_WIZARD_LOCAL => ['wizard-local'],
            self::SCENARIO_WIZARD_DETALHES => ['wizard-detalhes'],
            self::SCENARIO_WIZARD_IDENTIFICACAO => ['wizard-identificacao'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_imovel', 'bairro_id', 'endereco'], 'required', 'on' => self::SCENARIO_WIZARD_LOCAL],
            [
                'ocorrencia_tipo_problema_id',
                'exist',
                'skipOnEmpty' => true,
                'targetClass' => OcorrenciaTipoProblema::className(),
                'targetAttribute' => 'id',
                'on' => self::SCENARIO_WIZARD_DETALHES,
            ],
            [
                'descricao_outro_tipo_problema',
                'required',
                'when' => function ($model) {
                    return empty($model->ocorrencia_tipo_problema_id);
                },
                'skipOnError' => true,
                'on' => self::SCENARIO_WIZARD_DETALHES,
            ],
            [['cliente_id'], 'required', 'on' => self::SCENARIO_WIZARD_IDENTIFICACAO],
            [['ocorrencia_tipo_problema_id', 'tipo_imovel', 'bairro_id', 'cliente_id'], 'integer'],
            [['pontos_referencia', 'coordenadas', 'telefone', 'coordenadasJson', 'descricao_outro_tipo_problema'], 'safe'],
            ['email', 'email'],
            [['nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo'], 'string'],
        ];
    }

    public function beforeValidate()
    {
        $this->_validateAndLoadPostgisField();
        return parent::beforeValidate();
    }

    /**
     * Valida e carrega json de coordenadas em campo postgis
     * @return boolean
     */
    private function _validateAndLoadPostgisField()
    {
        if(!$this->coordenadasJson) {
            return true;
        }

        $this->coordenadas = new \yii\db\Expression($this->arrayToWkt('Point', explode(',',$this->coordenadasJson)));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return (new Ocorrencia)->attributeLabels();
    }

    public function persistSession()
    {
        if($this->file) {
            $this->nome_original_anexo = $this->file->baseName . '.' . $this->file->extension;
            $this->anexo = time() . '.' . $this->file->extension;
            $this->file->saveAs(OcorrenciaHelper::getUploadPath() . $this->anexo);
        }

        Yii::$app->session->set($this->session_name, serialize($this->attributes));
        return true;
    }

    public function clearSession()
    {
        Yii::$app->session->set($this->session_name,  false);
        return true;
    }

    public function loadFromSession()
    {
        $this->attributes = unserialize(Yii::$app->session->get($this->session_name));
        return true;
    }

    public function save()
    {
        $model = new Ocorrencia;
        foreach($this->ocorrenciaFields as $field) {
            $model->$field = $this->$field;
        }

        if ($this->ocorrencia_tipo_problema_id) {
            $model->descricao_outro_tipo_problema = null;
        }

        if (!$model->validate() || !$model->save()) {
            foreach ($model->errors as $attribute => $errors) {
                $this->addError($attribute, $errors);
            }
        }

        return !$this->hasErrors() && $this->clearSession() ? $model : false;
    }
}
