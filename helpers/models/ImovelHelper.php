<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;
use app\models\Imovel;

class ImovelHelper extends YiiStringHelper
{
    
    /**
     * Retorna endereço no formato
     * Rua Nome, Imovel Numero-Imovel Sequencia, Imovel Complemento, Imovel-Quarteirao Bairro 
     * @param Imovel $imovel
     * @param boolean $incluiCidade
     * @return string 
     */
    public static function getEnderecoCompleto(Imovel $imovel, $incluiCidade = true) {
    
        $str = '';
        $str .= $imovel->rua->nome . ', ';
        $str .= $imovel->numero ? $imovel->numero : 'S/N';
        
        if($imovel->sequencia)
            $str .= '-' . $imovel->sequencia;
        
        if($imovel->complemento)
            $str .= ', ' . $imovel->complemento;
        
        $quarteirao = $imovel->bairroQuarteirao;
        if($quarteirao && $incluiCidade) {
            
            $bairro = $quarteirao->bairro;
            
            if($bairro)
                $str .= ', Bairro ' . $bairro->nome;
        }
          
        return $str;
    }
    
    /**
     * Retorna endereço no formato
     * Rua Nome, Imovel Numero-Imovel Sequencia, Imovel Complemento
     * @param Imovel $imovel
     * @return string 
     */
    public static function getEndereco(Imovel $imovel) 
    {
        return self::getEnderecoCompleto($imovel,false);
    }
}