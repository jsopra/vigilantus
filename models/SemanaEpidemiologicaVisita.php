<?php

namespace app\models;
use app\components\ClienteActiveRecord;
use Yii;

/**
 * This is the model class for table "semana_epidemiologica_visitas".
 *
 * @property integer $id
 * @property integer $semana_epidemiologica_id
 * @property integer $bairro_id
 * @property integer $quarteirao_id
 * @property integer $agente_id
 * @property integer $cliente_id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property integer $visita_status_id
 * @property string $data_atividade
 *
 * @property BairroQuarteirao $quarteirao
 * @property Bairro $bairro
 * @property Cliente $cliente
 * @property EquipeAgente $agente
 * @property SemanaEpidemiologica $semanaEpidemiologica
 * @property Usuario $inseridoPor
 * @property Usuario $atualizadoPor
 * @property VisitaImovel[] $visitaImoveis
 */
class SemanaEpidemiologicaVisita extends ClienteActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'semana_epidemiologica_visitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semana_epidemiologica_id', 'bairro_id', 'quarteirao_id', 'agente_id', 'cliente_id', 'inserido_por'], 'required'],
            [['semana_epidemiologica_id', 'bairro_id', 'quarteirao_id', 'agente_id', 'cliente_id', 'inserido_por', 'atualizado_por', 'visita_status_id'], 'integer'],
            [['data_cadastro', 'data_atualizacao', 'data_atividade'], 'safe'],
            [['quarteirao_id'], 'exist', 'skipOnError' => true, 'targetClass' => BairroQuarteirao::className(), 'targetAttribute' => ['quarteirao_id' => 'id']],
            [['bairro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bairro::className(), 'targetAttribute' => ['bairro_id' => 'id']],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['agente_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipeAgente::className(), 'targetAttribute' => ['agente_id' => 'id']],
            [['semana_epidemiologica_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemanaEpidemiologica::className(), 'targetAttribute' => ['semana_epidemiologica_id' => 'id']],
            [['inserido_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['inserido_por' => 'id']],
            [['atualizado_por'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['atualizado_por' => 'id']],
            ['visita_status_id', 'default', 'value' => VisitaStatus::AGENDADA],
            ['visita_status_id', 'in', 'range' => VisitaStatus::getIDs()],
            ['quarteirao_id', 'unique', 'compositeWith' => ['semana_epidemiologica_id', 'agente_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'semana_epidemiologica_id' => 'Semana Epidemiológica',
            'bairro_id' => 'Bairro',
            'quarteirao_id' => 'Quarteirão',
            'agente_id' => 'Agente',
            'cliente_id' => 'Cliente',
            'inserido_por' => 'Inserido Por',
            'data_cadastro' => 'Data Cadastro',
            'atualizado_por' => 'Atualizado Por',
            'data_atualizacao' => 'Data Atualização',
            'visita_status_id' => 'Status da Visita',
            'data_atividade' => 'Data da Atividade',
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
    public function getBairro()
    {
        return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
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
    public function getAgente()
    {
        return $this->hasOne(EquipeAgente::className(), ['id' => 'agente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemanaEpidemiologica()
    {
        return $this->hasOne(SemanaEpidemiologica::className(), ['id' => 'semana_epidemiologica_id']);
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
    public function getVisitaImoveis()
    {
        return $this->hasMany(VisitaImovel::className(), ['semana_epidemiologica_visita_id' => 'id']);
    }
}
