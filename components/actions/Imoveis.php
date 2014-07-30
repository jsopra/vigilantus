<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Imovel;
use yii\helpers\Json;
use app\helpers\models\ImovelHelper;

class Imoveis extends Action
{
    public function run()
    {
        $queryString = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;
        
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;
        
        $array = [];

        $query = Imovel::find();
        $query->join = [
            ['INNER JOIN', 'ruas rua', 'imoveis.rua_id = rua.id'],
            ['INNER JOIN', 'bairro_quarteiroes quarteirao', 'imoveis.bairro_quarteirao_id = quarteirao.id'],
            ['INNER JOIN', 'bairros bairro', 'quarteirao.bairro_id = bairro.id']
        ];
 
        $strImovelRuaBairro = "rua.nome || ', ' || (CASE WHEN imoveis.numero IS NOT NULL AND imoveis.sequencia != '' THEN '-' || imoveis.numero ELSE 'S/N' END) || (CASE WHEN imoveis.sequencia IS NOT NULL AND imoveis.sequencia != '' THEN '-' || imoveis.sequencia ELSE '' END) || (CASE WHEN imoveis.sequencia IS NOT NULL AND imoveis.sequencia != '' THEN '-' || imoveis.sequencia ELSE '' END) || (CASE WHEN imoveis.sequencia IS NOT NULL AND imoveis.sequencia != '' THEN '-' || imoveis.sequencia ELSE '' END) || (CASE WHEN imoveis.complemento IS NOT NULL AND imoveis.complemento != '' THEN ', ' || imoveis.complemento ELSE '' END) || (CASE WHEN bairro.nome IS NOT NULL AND imoveis.complemento != '' THEN ', Bairro ' || bairro.nome ELSE '' END)";
  
        if($queryString)
            $query->andWhere($strImovelRuaBairro . ' ILIKE \'' . $queryString . '%\'');
        
        if($bairroID)
            $query->andWhere('bairro.id = ' . $bairroID);
        
        $imoveis = $query->all();

        foreach($imoveis as $imovel)
            $array[] = ['id' => $imovel->id, 'name' => ImovelHelper::getEnderecoCompleto($imovel)];

		echo Json::encode($array);
    }
}