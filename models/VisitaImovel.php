<?php

namespace app\models;
use app\components\ClienteActiveRecord;
use Yii;

/**
 * This is the model class for table "visita_imoveis".
 *
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property integer $semana_epidemiologica_visita_id
 * @property integer $visita_atividade_id
 * @property integer $rua_id
 * @property integer $quarteirao_id
 * @property string $logradouro
 * @property string $numero
 * @property string $sequencia
 * @property string $complemento
 * @property integer $tipo_imovel_id
 * @property string $hora_entrada
 * @property integer $visita_tipo
 * @property integer $pendencia
 * @property integer $depositos_eliminados
 * @property string $numero_amostra_inicial
 * @property string $numero_amostra_final
 * @property integer $quantidade_tubitos
 *
 * @property BairroQuarteiroes $quarteirao
 * @property Clientes $cliente
 * @property ImovelTipos $tipoImovel
 * @property Ruas $rua
 * @property SemanaEpidemiologicaVisitas $semanaEpidemiologicaVisita
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 * @property VisitaImovelDepositos[] $visitaImovelDepositos
 */
class VisitaImovel extends ClienteActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_imoveis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id', 'inserido_por', 'semana_epidemiologica_visita_id', 'quarteirao_id', 'visita_tipo'], 'required'],
            [['cliente_id', 'inserido_por', 'atualizado_por', 'semana_epidemiologica_visita_id', 'visita_atividade_id', 'rua_id', 'quarteirao_id', 'tipo_imovel_id', 'visita_tipo', 'pendencia', 'depositos_eliminados', 'quantidade_tubitos'], 'integer'],
            [['data_cadastro', 'data_atualizacao', 'hora_entrada'], 'safe'],
            [['logradouro', 'numero', 'sequencia', 'complemento', 'numero_amostra_inicial', 'numero_amostra_final'], 'string'],
            [['quarteirao_id'], 'exist', 'skipOnError' => true, 'targetClass' => BairroQuarteirao::className(), 'targetAttribute' => ['quarteirao_id' => 'id']],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['tipo_imovel_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImovelTipo::className(), 'targetAttribute' => ['tipo_imovel_id' => 'id']],
            [['rua_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rua::className(), 'targetAttribute' => ['rua_id' => 'id']],
            [['semana_epidemiologica_visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemanaEpidemiologicaVisita::className(), 'targetAttribute' => ['semana_epidemiologica_visita_id' => 'id']],
            [['inserido_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['inserido_por' => 'id']],
            [['atualizado_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['atualizado_por' => 'id']],
            ['visita_tipo', 'in', 'range' => VisitaTipo::getIDS()],
            ['pendencia', 'in', 'range' => VisitaPendencia::getIDS()],

            ['visita_atividade_id', 'in', 'range' => VisitaAtividade::getIDs()],

            [['visita_atividade_id', 'hora_entrada', 'logradouro', 'numero', 'tipo_imovel_id'], 'required'],

            [
                ['numero_amostra_final', 'quantidade_tubitos'],
                'required',
                'when' => function ($model) {
                    return !is_null($model->numero_amostra_inicial);
                },
            ],
        ];
    }

    public function beforeSave($insert)
    {
        $this->_setRua();

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = NULL)
    {
        $transaction = $this->getDb()->beginTransaction();

        try {
            
            if (!parent::save($runValidation, $attributes)) {
                $transaction->rollback();
                return false;
            }
die('a');
            if ($this->numero_amostra_inicial || $this->numero_amostra_final) {

                $inicial = $this->numero_amostra_inicial;
                $final = $this->numero_amostra_final;

                while ($inicial <= $final) {

                    $amostra = new AmostraTransmissor;
                    $amostra->visita_id = $this->id;
                    $amostra->data_coleta = $this->data_cadastro;
                    $amostra->cliente_id = $this->cliente_id;
                    $amostra->quarteirao_id = $this->quarteirao_id;
                    $amostra->endereco = $this->logradouro;
                    $amostra->numero_casa = $this->numero;
                    $amostra->numero_amostra = $inicial;
                    if (!$amostra->save()) {
                        $this->addError('id', 'Erro ao salvar amostras coletadas');
                        $transaction->rollback();
                        return false;
                    }

                    $inicial++;
                }
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        $transaction->rollback();
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente',
            'inserido_por' => 'Inserido Por',
            'data_cadastro' => 'Data Cadastro',
            'atualizado_por' => 'Atualizado Por',
            'data_atualizacao' => 'Data Atualização',
            'semana_epidemiologica_visita_id' => 'Visita de Semana Epidemiológica',
            'visita_atividade_id' => 'Atividade da Visita',
            'rua_id' => 'Rua',
            'quarteirao_id' => 'Quarteirão',
            'logradouro' => 'Logradouro',
            'numero' => 'Número',
            'sequencia' => 'Sequência',
            'complemento' => 'Complemento',
            'tipo_imovel_id' => 'Tipo do Imóvel',
            'hora_entrada' => 'Hora Entrada',
            'visita_tipo' => 'Tipo da Visita',
            'pendencia' => 'Pendência',
            'depositos_eliminados' => 'Depósitos Eliminados',
            'numero_amostra_inicial' => 'Número da Amostra Inicial',
            'numero_amostra_final' => 'Número da Amostra Final',
            'quantidade_tubitos' => 'Quantidade de Tubitos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarteirao()
    {
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'quarteirao_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoImovel()
    {
        return $this->hasOne(ImovelTipo::className(), ['id' => 'tipo_imovel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRua()
    {
        return $this->hasOne(Rua::className(), ['id' => 'rua_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemanaEpidemiologicaVisita()
    {
        return $this->hasOne(SemanaEpidemiologicaVisita::className(), ['id' => 'semana_epidemiologica_visita_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInseridoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaImovelDepositos()
    {
        return $this->hasMany(VisitaImovelDeposito::className(), ['visita_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaImovelTratamentos()
    {
        return $this->hasMany(VisitaImovelTratamento::className(), ['visita_id' => 'id']);
    }

    /**
     * Busca ou cria um objeto Rua, e seta o $this->rua_id
     * @return boolean
     */
    private function _setRua()
    {
        $rua = Rua::find()->daRua($this->logradouro)->one();

        if (!$rua) {

            $rua = new Rua;
            $rua->cliente_id = $this->cliente_id;
            $rua->municipio_id  = $this->cliente ? $this->cliente->municipio_id : null;
            $rua->nome = $this->logradouro;

            if (!$rua->save()) {
                return false;
            }
        }

        $this->rua_id = $rua->id;

        return true;
    }
}
