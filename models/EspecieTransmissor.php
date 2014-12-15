<?php

namespace app\models;

use app\components\ClienteActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "especies_transmissores".
 *
 * Estas são as colunas disponíveis na tabela 'especies_transmissores':
 * @property integer $id
 * @property integer $cliente_id
 * @property string $nome
 * @property integer $qtde_metros_area_foco
 * @property integer $qtde_dias_permanencia_foco
 * @property string $cor_foco_no_mapa
 */
class EspecieTransmissor extends ClienteActiveRecord
{
    const COR_FOCO_DEFAULT = '#000000';

    public $doencas;

    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'especies_transmissores';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return [
            [['cliente_id', 'qtde_metros_area_foco', 'qtde_dias_permanencia_foco', 'nome'], 'required'],
            [['cor_foco_no_mapa', 'doencas'], 'safe'],
            [['cor_foco_no_mapa'], 'string', 'max' => 7, 'skipOnEmpty' => true],
            ['cliente_id', 'exist', 'targetClass' => Cliente::className(), 'targetAttribute' => 'id'],
            ['nome', 'unique', 'compositeWith' => 'cliente_id'],
            [['qtde_metros_area_foco', 'qtde_dias_permanencia_foco'], 'integer'],
        ];
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cliente_id' => 'Município Cliente',
            'nome' => 'Nome',
            'qtde_metros_area_foco' => 'Área de foco (metros)',
            'qtde_dias_permanencia_foco' => 'Permanência do foco (dias)',
            'cor_foco_no_mapa' => 'Cor do foco no Mapa',
            'doencas' => 'Doenças'
        );
    }

    public function afterFind()
    {
        foreach($this->doencasEspecie as $doenca) {
            $this->doencas[] = $doenca->doenca_id;
        }

        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = NULL) {

        $currentTransaction = $this->getDb()->getTransaction();
        $newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();

        try {

            $result = parent::save($runValidation, $attributes);

            if ($result) {

                $salvouDoencas = true;

                if($this->doencas) {

                    EspecieTransmissorDoenca::deleteAll('especie_transmissor_id = :transmissor', [':transmissor' => $this->id]);

                    foreach($this->doencas as $doenca) {

                        $etd = new EspecieTransmissorDoenca;
                        $etd->cliente_id = $this->cliente_id;
                        $etd->doenca_id = $doenca;
                        $etd->especie_transmissor_id = $this->id;

                        if(!$etd->save()) {
                            $salvouDoencas = false;
                            break;
                        }
                    }
                }

                if($salvouDoencas) {

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

    /**
     * Busca cor do foco no mapa, considerando o default em caso de null para a espécie
     * @return string
     */
    public function getCor()
    {
        return $this->cor_foco_no_mapa ? $this->cor_foco_no_mapa : self::COR_FOCO_DEFAULT;
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getDoencasEspecie()
    {
        return $this->hasMany(EspecieTransmissorDoenca::className(), ['especie_transmissor_id' => 'id']);
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->clearRelationships();

        return $parent;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function clearRelationships()
    {
        EspecieTransmissorDoenca::deleteAll('especie_transmissor_id = :especie', [':especie' => $this->id]);
    }
}
