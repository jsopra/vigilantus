<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\models\MunicipioHelper;
use Exception;
use Yii;
use yii\web\UploadedFile;

/**
 * Este é a classe de modelo da tabela "municipios".
 *
 * Estas são as colunas disponíveis na tabela 'municipios':
 * @property integer $id
 * @property string $nome
 * @property string $sigla_estado
 * @property string $coordenadas_area
 * @property string $brasao
 * @property string $slug
 */
class Municipio extends ActiveRecord
{
    public $latitude;
    public $longitude;
    public $file;

    public $brasaoSizes = [
        // folder, width, height
        ['mini', 50, 50],
        ['small', 75, 75],
        ['normal', 150, 150],
        ['large', 300, 300]
    ];

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
            [['nome', 'sigla_estado', 'coordenadas_area', 'slug'], 'required'],
            ['sigla_estado', 'string', 'max' => 2],
            ['nome', 'unique', 'compositeWith' => 'sigla_estado'],
            [['coordenadas_area'], 'string'],
            [['file'], 'file', 'extensions' => 'png', 'mimeTypes' => 'image/png'],
            ['brasao', 'safe'],
            ['slug', 'unique'],
        ];
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'sigla_estado' => 'UF',
            'coordenadas_area' => 'Coordenadas',
            'brasao' => 'Brasão',
            'file' => 'Brasão',
            'slug' => 'URL',
        ];
    }

    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        throw new Exception(Yii::t('Site', 'Exclusão desabilitada'), 500);
    }

    /**
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['municipio_id' => 'id']);
    }

    /**
     * @return AtiveQuery
     */
    public function getOcorrencias()
    {
        return $this->hasMany(Ocorrencia::className(), ['municipio_id' => 'id']);
    }

    /**
     * @return AtiveQuery
     */
    public function getBairros()
    {
        return $this->hasMany(Bairro::className(), ['municipio_id' => 'id']);
    }

    /**
     * Busca municípios
     * @param int $id Default is null
     * @return Cliente[]
     */
    public static function getMunicipios($id = null)
    {
        $query = self::find();
        $query->joinWith('cliente');

        if ($id) {
            $query->andWhere(['"municipios"."id"' => $id]);
        }

        return $query->all();
    }

    /**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas()
    {
        if(!$this->coordenadas_area) {
            return false;
        }

        if($this->longitude && $this->latitude) {
            return true;
        }

        list($this->longitude, $this->latitude) = $this->wktToArray('Point', 'coordenadas_area');

        return true;
    }

    /**
     * Busca coordenadas de bairros
     * @param array $except
     * @return array
     */
    public function getCoordenadasBairros(array $except)
    {
        $return = [];

        $bairros = Bairro::find()->comCoordenadas()->all();

        foreach($bairros as $bairro) {

            if(in_array($bairro->id,$except))
                continue;

            $bairro->loadCoordenadas();
            $return[] = ['nome' => $bairro->nome, 'coordenadas' => $bairro->coordenadas];
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = null)
    {
        $currentTransaction = $this->db->getTransaction();
        $newTransaction = $currentTransaction ? null : $this->db->beginTransaction();

        try {

            $this->file = UploadedFile::getInstance($this, 'file');

            if ($this->file) {
                MunicipioHelper::saveBrasao($this, $this->file);
                $this->brasao = $this->id . '.' . $this->file->extension;
            }

            if (parent::save($runValidation, $attributes)) {

                if ($newTransaction) {
                    $newTransaction->commit();
                }
                return true;
            }

        } catch (\Exception $e) {}

        if ($newTransaction) {
            $newTransaction->rollback();
        }

        return false;
    }

    /**
     * Nome do setor responsável pelo uso do sistema.
     * @return string|null nome do setor.
     */
    public function getSetorResponsavel()
    {
        if ($this->cliente) {
            return Configuracao::getValorConfiguracaoParaCliente(
                Configuracao::ID_SETOR_UTILIZA_FERRAMENTA,
                $this->cliente->id
            );
        }

        return;
    }

    public function coordenadaNaCidade($lat, $lon)
    {
        $query = "
            id IN (
                SELECT DISTINCT b.id
                FROM bairros b
                JOIN municipios m ON m.id = b.municipio_id
                WHERE m.id = " . $this->id . " AND ST_Contains(b.coordenadas_area, ST_SetSRID(ST_Point(" . $lon . ", " . $lat . "),4326))
            )
        ";

        return Bairro::find()->andWhere($query)->count() > 0;
    }
}
