<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "ocorrencia_tipos_problemas".
 *
 * Estas são as colunas disponíveis na tabela "ocorrencia_tipos_problemas":
 * @property integer $id
 * @property integer $cliente_id
 * @property string $nome
 * @property boolean $ativo
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 *
 * @property Ocorrencia[] $ocorrencias
 * @property Cliente $cliente
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class OcorrenciaTipoProblema extends ClienteActiveRecord
{

	public function beforeDelete()
    {
        if ($this->getOcorrencias()->count() > 0) {
            throw new \Exception('O tipo tem denúncias vinculadas. Desative-o para não usar mais.');
        }

        return parent::beforeDelete();
    }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ocorrencia_tipos_problemas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'nome', 'inserido_por'], 'required'],
			[['cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['nome'], 'string'],
			[['ativo'], 'boolean'],
			['nome', 'unique', 'compositeWith' => ['cliente_id']],
			[['data_cadastro', 'data_atualizacao'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'cliente_id' => 'Município Cliente',
			'nome' => 'Nome',
			'ativo' => 'Ativo',
			'inserido_por' => 'Inserido Por',
			'data_cadastro' => 'Data Cadastro',
			'atualizado_por' => 'Atualizado Por',
			'data_atualizacao' => 'Data Atualizacao',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getOcorrencias()
	{
		return $this->hasMany(Ocorrencia::className(), ['ocorrencia_tipo_problema_id' => 'id']);
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
	 * Tipos de problemas padrão para municípios que não cadastraram seus
	 * próprios tipos personalizados na tabela. Esses valores são preenchido
	 * no campo "outro_tipo_problema" das ocorrências.
	 * @return string[]
	 */
	public static function getTiposPadrao()
	{
		return [
			'abelhas_vespas' => 'Abelhas e vespas',
			'animais_perimetro_urbano' => 'Animais em perímetro urbano',
			'aranhas_escorpioes_chilopodos' => 'Aranhas, escorpiões e Chilopodos',
			'boca_lobo' => 'Boca de lobo',
			'caixa_agua_tanque' => 'Caixas de água, tanques ...',
			'calhas' => 'Calhas',
			'canos_parabolica' => 'Canos de parabólica',
			'construcoes_abandonadas' => 'Construções abandonadas',
			'corujas' => 'Corujas',
			'formigas' => 'Formigas',
			'lages_com_agua' => 'Lages com água',
			'lesmas_caracois' => 'Lesmas e caracóis',
			'lixo' => 'Lixo',
			'morcegos' => 'Morcegos',
			'moscas_mosquitos' => 'Moscas e mosquitos',
			'percevejos' => 'Percevejos',
			'piscina_lago_artificial' => 'Piscina, lago artificial ...',
			'pneus' => 'Pneus',
			'pulgas_piolhos_bichos_de_pe' => 'Pulgas, piolhos e bichos-de-pé',
			'roedores' => 'Roedores',
			'saneamento' => 'Saneamento (fossa aberta, riacho ...)',
			'serpentes' => 'Serpentes',
			'sucatas_pecas' => 'Sucatas, peças, ...',
			'taturanas_lagartas' => 'Taturanas e outras lagartas',
			'terreno_abandonado' => 'Terreno abandonado',
		];
	}
}
