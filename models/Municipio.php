<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\models\MunicipioHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use app\helpers\ImageHelper;

/**
 * Este é a classe de modelo da tabela "municipios".
 *
 * Estas são as colunas disponíveis na tabela 'municipios':
 * @property integer $id
 * @property string $nome
 * @property string $sigla_estado
 * @property string $coordenadas_area
 * @property string $brasao
 */
class Municipio extends ActiveRecord
{
    public $latitude;
    public $longitude;
    public $file;

    public $brasaoSizes =[
        /* folder, width, height */
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
            [['nome', 'sigla_estado', 'coordenadas_area'], 'required'],
            ['sigla_estado', 'string', 'max' => 2],
            ['nome', 'unique', 'compositeWith' => 'sigla_estado'],
            [['coordenadas_area'], 'string'],
            [['file'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            ['brasao', 'safe'],
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
            'coordenadas_area' => 'Coordenadas',
            'brasao' => 'Brasão',
            'file' => 'Brasão',
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

        if($id) {
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
    public function getCoordenadasBairros(array $except) {

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
    public function save($runValidation = true, $attributes = NULL) {

        $currentTransaction = $this->getDb()->getTransaction();
        $newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();

        try {

            $this->file = UploadedFile::getInstance($this, 'file');

            $salvouImagem = true;

            $result = false;

            if($this->file) {

                $path = MunicipioHelper::getBrasaoPath($this, true);

                if(!is_dir($path)) {
                    mkdir($path);
                    mkdir($path . 'original/');

                    foreach($this->brasaoSizes as $size) {
                        mkdir($path . $size[0]);
                    }
                }

                $imagemOriginal = $this->file->saveAs($path . 'original/' . $this->file->baseName . '.' . $this->file->extension, false);

                list($originalWidth, $originalHeight) = getimagesize($path . 'original/' . $this->file->baseName . '.' . $this->file->extension);

                foreach($this->brasaoSizes as $size) {

                    $folder = $size[0];
                    $width = $size[1];
                    $height = $size[2];

                    $size = ImageHelper::calculateDimensions($originalWidth, $originalHeight, $width, $height);
                    $image = Image::thumbnail($path . 'original/' . $this->file->baseName . '.' . $this->file->extension, $size['width'], $size['height']);
                    $thumb = $image->save($path . $folder . '/' . $this->file->baseName . '.' . $this->file->extension);

                    if(!$thumb) {
                        $salvouImagem = false;
                        break;
                    }
                }

                if($salvouImagem) {
                    $this->brasao = $this->file->baseName . '.' . $this->file->extension;
                }

            }

            if ($salvouImagem) {

                $result = parent::save($runValidation, $attributes);

                if($result) {

                    if($newTransaction) {
                        $newTransaction->commit();
                    }
                }
                else {
                    if($newTransaction) {
                        $newTransaction->rollback();
                    }

                    $result = false;
                }
            }
            else {
                if($newTransaction) {
                    $newTransaction->rollback();
                }
            }
        }
        catch (\Exception $e) {
            if($newTransaction) {
                $newTransaction->rollback();
            }
            throw $e;
        }

        return $result;
    }

    public function coordenadaNaCidade($lat, $lon)
    {
        $return = [];

        $query = "
            id IN (
                SELECT DISTINCT b.id
                FROM bairros b
                WHERE ST_Contains(b.coordenadas_area, ST_SetSRID(ST_Point(" . $lon . ", " . $lat . "),4326))
            )
        ";

        return Bairro::find()->andWhere($query)->count() > 0;
    }
}
