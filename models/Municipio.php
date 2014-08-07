<?php

namespace app\models;

use app\components\PostgisActiveRecord;

/**
 * Este é a classe de modelo da tabela "municipios".
 *
 * Estas são as colunas disponíveis na tabela 'municipios':
 * @property integer $id
 * @property string $nome
 * @property string $sigla_estado
 * @property string $nome_contato
 * @property string $email_contato
 * @property string $telefone_contato
 * @property string $departamento
 * @property string $cargo
 * @property string $coordenadas_area
 */
class Municipio extends PostgisActiveRecord
{
    public $latitude;
    public $longitude;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'municipios';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return [
            [['nome', 'sigla_estado', 'nome_contato', 'telefone_contato', 'departamento'], 'required'],
            ['sigla_estado', 'string', 'max' => 2],
            [['email_contato', 'cargo'], 'safe'],
            ['nome', 'unique', 'compositeWith' => 'sigla_estado'],
            [['coordenadas_area'], 'string']
        ];
    }

    /**
     * @return array regras de relações
     */
    public function relations()
    {
        // AVISO: você talvez tenha de ajustar o nome da relação gerada.
        return array(
            'usuarios' => array(self::HAS_MANY, 'Usuarios', 'municipio_id'),
        );
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nome' => 'Nome',
            'sigla_estado' => 'Estado Sigla',
            'nome_contato' => 'Nome do contato',
            'email_contato' => 'Email do contato',
            'telefone_contato' => 'Telefone do contato',
            'departamento' => 'Departamento do contato',
            'cargo' => 'Cargo do contato',
            'coordenadas_area' => 'Coordenadas',
        );
    }

    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        throw new \Exception(\Yii::t('Site', 'Exclusão não habilitada'), 500);
    }
    
    /**
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['municipio_id' => 'id']);
    }
    
    /**
     * Busca municípios
     * @param int $id Default is null
     * @return Cliente[] 
     */
    public static function getMunicipios($id = null) {
        
        $query = self::find();
        
        $query->joinWith('cliente');

        if($id)
            $query->andWhere(['"id"' => $id]);
        
        return $query->all();
    }
    
    /**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas() {

        if(!$this->coordenadas_area) 
            return false;
        
        if($this->latitude && $this->longitude)
            return true;
        
        list($this->latitude, $this->longitude) = $this->postgisToArray('Point', 'coordenadas_area');
        
        return true;
        
    } 
    
    /**
     * Busca coordenadas de bairros
     * @param array $except
     * @return array 
     */
    public function getCoordenadasBairros(array $except) {
        
        $return = [];
        
        $bairros = Bairro::find()->comCoordenadas()->all();
        
        foreach($bairros as $bairro) {
            
            if(in_array($bairro->id,$except)) 
                continue;
            
            $bairro->loadCoordenadas();
            $return[] = $bairro->coordenadas;
        }
        
        return $return;
    }
}
