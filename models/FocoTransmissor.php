<?php

namespace app\models;

use Yii;
use app\components\ClienteActiveRecord;

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
 * @property integer $bairro_quarteirao_id
 * @property string $planilha_endereco
 * @property integer $planilha_imovel_tipo_id
 * @property integer $cliente_id
 *
 * @property Usuario $inseridoPor
 * @property Usuario $atualizadoPor
 * @property DepositoTipo $tipoDeposito
 * @property EspecieTransmissor $especieTransmissor
 */
class FocoTransmissor extends ClienteActiveRecord
{
    public $categoria_id;
    public $bairro_id;
    public $foco_ativo;
    public $imovel_lira;

    public $mes;
    public $quantidade_registros;

    const QTDE_DIAS_INFORMACAO_PUBLICA = 60;

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
            [['cliente_id', 'inserido_por', 'tipo_deposito_id', 'especie_transmissor_id', 'bairro_quarteirao_id', 'data_entrada', 'data_exame', 'data_coleta', 'quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'], 'required'],
            [['quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'], 'integer', 'min' => 0],
            [['!inserido_por', '!atualizado_por'], 'exist', 'targetClass' => Usuario::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['tipo_deposito_id', 'exist', 'targetClass' => DepositoTipo::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['cliente_id', 'exist', 'targetClass' => Cliente::className(), 'targetAttribute' => 'id'],
            ['imovel_id', 'exist', 'targetClass' => Imovel::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['bairro_quarteirao_id', 'exist', 'targetClass' => BairroQuarteirao::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['planilha_imovel_tipo_id', 'exist', 'targetClass' => ImovelTipo::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['!data_cadastro', '!data_atualizacao', 'data_entrada', 'data_exame', 'data_coleta'], 'date'],
            [['laboratorio', 'tecnico'], 'string', 'max' => 256],
            [['planilha_imovel_tipo_id', 'planilha_endereco', 'mes', 'quantidade_registros'], 'safe'],
            ['planilha_imovel_tipo_id', 'required', 'when' => function($model) {
                return $model->planilha_endereco != '';
            }, 'whenClient' => "function (attribute, value) {
                return novoEndereco == true;
            }"],
            ['imovel_id', 'required', 'when' => function($model) {
                return $model->planilha_endereco == '';
            }, 'whenClient' => "function (attribute, value) {
                return novoEndereco == false;
            }"]
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
            'categoria_id' => 'Categoria de Bairro',
            'foco_ativo' => 'Foco Ativo',
            'imovel_lira' => 'Imóvel LIRA',
            'bairro_quarteirao_id' => 'Quarteirão',
            'planilha_endereco' => 'Endereço',
            'planilha_imovel_tipo_id' => 'Tipo do Imóvel',
            'cliente_id' => 'Município Cliente',
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
     * @return \yii\db\ActiveRelation
     */
    public function getBairroQuarteirao()
    {
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
    }

    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'planilha_imovel_tipo_id']);
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

    /*
     * Popula ID do bairro pelo quarteirão cadastrado
     */
    public function popularBairro() {

        $this->bairro_id = $this->bairroQuarteirao->bairro_id;
    }

    public function isInformacaoPublica()
    {
        $dataAtual = new \DateTime;
        $dataAtual->modify('-' . $this->getQuantidadeDiasInformacaoPublica() . ' days');

        $dataColeta = new \DateTime($this->data_coleta);

        return $dataColeta >= $dataAtual;
    }

    public function getQuantidadeDiasInformacaoPublica()
    {
        $valor = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA, $this->cliente_id);

        if(!$valor) {
            return self::QTDE_DIAS_INFORMACAO_PUBLICA;
        }

        if($valor < self::QTDE_DIAS_INFORMACAO_PUBLICA) {
            return self::QTDE_DIAS_INFORMACAO_PUBLICA;
        }

        return $valor;
    }

    public function getAreaTratamento()
    {
        $return = [];

        $query = "
            id IN (
                SELECT DISTINCT br.id
                FROM focos_transmissores ft
                JOIN especies_transmissores et ON ft.especie_transmissor_id = et.id
                JOIN bairro_quarteiroes bf on ft.bairro_quarteirao_id = bf.id
                LEFT JOIN bairro_quarteiroes br ON st_dwithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area)::geography, qtde_metros_area_foco)
                WHERE ft.id = " . $this->id . " AND br.id <> " . $this->bairroQuarteirao->id . "
            )
        ";

        return BairroQuarteirao::find()->andWhere($query)->all();
    }
}
