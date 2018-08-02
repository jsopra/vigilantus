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
 * @property integer $focal_imovel_tratamento
 * @property integer $focal_larvicida_tipo
 * @property double $focal_larvicida_qtde_gramas
 * @property integer $focal_larvicida_qtde_dep_tratado
 * @property integer $perifocal_adulticida_tipo
 * @property double $perifocal_adulticida_qtde_cargas
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
    const SCENARIO_EXECUCAO_VISITA = 'execucaoVisita';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_imoveis';
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_EXECUCAO_VISITA => ['execucaoVisita'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id', 'inserido_por', 'semana_epidemiologica_visita_id', 'quarteirao_id', 'visita_tipo'], 'required'],
            [['cliente_id', 'inserido_por', 'atualizado_por', 'semana_epidemiologica_visita_id', 'visita_atividade_id', 'rua_id', 'quarteirao_id', 'tipo_imovel_id', 'visita_tipo', 'pendencia', 'depositos_eliminados', 'quantidade_tubitos', 'focal_imovel_tratamento', 'focal_larvicida_tipo', 'focal_larvicida_qtde_dep_tratado', 'perifocal_adulticida_tipo'], 'integer'],
            [['data_cadastro', 'data_atualizacao', 'hora_entrada'], 'safe'],
            [['logradouro', 'numero', 'sequencia', 'complemento', 'numero_amostra_inicial', 'numero_amostra_final'], 'string'],
            [['focal_larvicida_qtde_gramas', 'perifocal_adulticida_qtde_cargas'], 'number'],
            [['quarteirao_id'], 'exist', 'skipOnError' => true, 'targetClass' => BairroQuarteiroes::className(), 'targetAttribute' => ['quarteirao_id' => 'id']],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['tipo_imovel_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImovelTipos::className(), 'targetAttribute' => ['tipo_imovel_id' => 'id']],
            [['rua_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ruas::className(), 'targetAttribute' => ['rua_id' => 'id']],
            [['semana_epidemiologica_visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemanaEpidemiologicaVisitas::className(), 'targetAttribute' => ['semana_epidemiologica_visita_id' => 'id']],
            [['inserido_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['inserido_por' => 'id']],
            [['atualizado_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['atualizado_por' => 'id']],
            /*
            'rua_id' => 'integer references ruas(id)',
            'quarteirao_id' => 'integer not null references bairro_quarteiroes(id)',
            */

            ['visita_atividade_id', 'in', 'range' => VisitaAtividade::getIDs()],

            [['visita_atividade_id', 'hora_entrada', 'logradouro', 'numero', 'tipo_imovel_id', ''], 'required', 'on' => ['execucaoVisita']],

            [
                ['numero_amostra_final', 'quantidade_tubitos'],
                'required',
                'when' => function ($model) {
                    return !is_null($model->numero_amostra_inicial);
                },
                'on' => ['execucaoVisita']
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
            'focal_imovel_tratamento' => 'Focal Imovel Tratamento',
            'focal_larvicida_tipo' => 'Focal Larvicida Tipo',
            'focal_larvicida_qtde_gramas' => 'Focal Larvicida Qtde Gramas',
            'focal_larvicida_qtde_dep_tratado' => 'Focal Larvicida Qtde Dep Tratado',
            'perifocal_adulticida_tipo' => 'Perifocal Adulticida Tipo',
            'perifocal_adulticida_qtde_cargas' => 'Perifocal Adulticida Qtde Cargas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarteirao()
    {
        return $this->hasOne(BairroQuarteiroes::className(), ['id' => 'quarteirao_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoImovel()
    {
        return $this->hasOne(ImovelTipos::className(), ['id' => 'tipo_imovel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRua()
    {
        return $this->hasOne(Ruas::className(), ['id' => 'rua_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemanaEpidemiologicaVisita()
    {
        return $this->hasOne(SemanaEpidemiologicaVisitas::className(), ['id' => 'semana_epidemiologica_visita_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInseridoPor()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'inserido_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'atualizado_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaImovelDepositos()
    {
        return $this->hasMany(VisitaImovelDepositos::className(), ['visita_id' => 'id']);
    }
}
