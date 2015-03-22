<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "clientes".
 *
 * Estas são as colunas disponíveis na tabela "clientes":
 * @property integer $id
 * @property integer $municipio_id
 * @property string $data_cadastro
 * @property string $nome_contato
 * @property string $email_contato
 * @property string $telefone_contato
 * @property string $departamento
 * @property string $cargo
 * @property string $rotulo
 *
 * @property Municipios $municipio
 */
class Cliente extends ActiveRecord
{
    protected $_validateMunicipio = false;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'clientes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        return [
			[['municipio_id', 'nome_contato', 'telefone_contato', 'departamento'], 'required'],
            [['municipio_id'], 'unique'],
            [['rotulo'], 'unique'],
            ['municipio_id', 'exist', 'targetClass' => Municipio::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['data_cadastro', 'brasao', 'email_contato', 'cargo'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Município',
			'data_cadastro' => 'Data de Cadastro',
            'nome_contato' => 'Nome do contato',
            'email_contato' => 'Email do contato',
            'telefone_contato' => 'Telefone do contato',
            'departamento' => 'Departamento do contato',
            'cargo' => 'Cargo do contato',
            'rotulo' => 'Rótulo'
		];
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
    public function getModulos()
    {
        return $this->hasMany(ClienteModulo::className(), ['cliente_id' => 'id']);
    }

    /**
     * @return int
     */
    public function getQuantidadeModulos()
    {
        return ClienteModulo::find()->where(['cliente_id' => $this->id])->count();
    }

    /**
     * @param int $moduloId
     * @return boolean
     */
    public function moduloIsHabilitado($moduloId)
    {
        $this->refresh();

        foreach($this->modulos as $modulo) {
            if($modulo->modulo_id == $moduloId) {
                return true;
            }
        }

        return false;
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->_clearRelationships();

        return $parent;
    }

     /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = NULL) {

        $transaction = $this->getDb()->beginTransaction();

        try {

            $result = parent::save($runValidation, $attributes);

            if ($result) {

                $configuracoes = Configuracao::find()->all();
                foreach($configuracoes as $configuracao) {

                    //adiciona via command, pois do contrário vai forçar o cliente_id da sessão logada
                    \Yii::$app->db->createCommand()->insert('configuracoes_clientes', [
                        'configuracao_id' => $configuracao->id,
                        'cliente_id' => $this->id,
                        'valor' =>$configuracao->valor,
                    ])->execute();
                }
                $transaction->commit();
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
     * @return boolean
     */
    public function hasNetwork($id)
    {
        return SocialAccount::find()->doCliente($this->id)->daRede($id)->count() > 0;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function _clearRelationships()
    {
        ClienteModulo::deleteAll('cliente_id = :cliente', [':cliente' => $this->id]);
        ConfiguracaoCliente::deleteAll('cliente_id = :cliente', [':cliente' => $this->id]);
    }
}
