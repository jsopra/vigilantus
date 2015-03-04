<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BoletimRg;
use yii\base\Model;
use app\models\ImovelTipo;

class ResumoRgBairroReport extends Model
{
    public $bairro_id;
    public $lira;
    protected $_tiposImoveis;

    public function rules()
    {
        return [
            [['bairro_id', 'lira'], 'required'],
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
            ['lira', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bairro_id' => 'Bairro',
            'lira' => 'Tipo',
        ];
    }

    /**
     * @return array
     */
    public function getTiposImoveis()
    {
        if (null === $this->_tiposImoveis) {

            $this->_tiposImoveis = [];
            $dados = ImovelTipo::find()->ativo()->all();
            foreach ($dados as $imovelTipo)
                $this->_tiposImoveis[$imovelTipo->id] = $imovelTipo->sigla ? $imovelTipo->sigla : $imovelTipo->nome;

        }

        return $this->_tiposImoveis;
    }

    /**
     * @return array
     * <code>
     * 'id_quarteirao' => [
     *     'quarteirao' => 1,
     *     'quarteirao_numero_alternativo' => 2,
     *     'quarteirao_sequencia' => null,
     *     'imoveis' => [
     *         id_tipo => valor,
     *         ...
     *     ],
     * ],
     * ...
     * </code>
     */
    public function getData()
    {
        $data = [];

        $query = BoletimRg::find();
        $query->where(['bairro_id' => $this->bairro_id]);
        $query->orderBy('bairro_quarteirao_id');

        // Somente do tipo LIRA correto
        if($this->lira !== '') {
            $query->andWhere('
                boletins_rg.id IN (
                    SELECT boletim_rg_id
                    FROM boletim_rg_fechamento
                    WHERE imovel_lira = ' . ($this->lira ? 'TRUE' : 'FALSE') . '
                )'
            );
        }

        // Se tiver dois boletins pro mesmo quarteirão, pega o mais recente
        $query->andWhere('
            data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');

        foreach ($query->all() as $boletimRg) {

            $valores = [
                'quarteirao' => $boletimRg->quarteirao->numero_quarteirao,
                'quarteirao_numero_alternativo' => $boletimRg->quarteirao->numero_quarteirao_2,
                'quarteirao_sequencia' => $boletimRg->quarteirao->seq,
                'data_ultimo_foco' => $boletimRg->quarteirao->data_ultimo_foco,
                'imoveis' => [],
            ];

            foreach ($this->tiposImoveis as $idTipo => $tipoImovel)
                $valores['imoveis'][$idTipo] = 0;

            $queryFechamentos = $boletimRg->getBoletinsFechamento();

            if($this->lira !== '')
                $queryFechamentos->where('imovel_lira = ' . ($this->lira ? 'TRUE' : 'FALSE'));

            foreach ($queryFechamentos->all() as $fechamento)
                $valores['imoveis'][$fechamento->imovel_tipo_id] += $fechamento->quantidade;


            $data[$boletimRg->bairro_quarteirao_id] = $valores;
        }

        return $data;
    }
}
