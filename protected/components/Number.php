<?php
/**
 * Classe para parsear e formatar números internacionalizados
 */
class Number
{
    /**
     * Converte número (quebrado ou não) no formato inteiro
     * @param string $numero 
     */
    public static function numeroInteiro($numero)
    {
        $numero = self::parse($numero);
        
        return Yii::app()->numberFormatter->format(self::getFormat(0), $numero);
    }
    
    /**
     * Converte número no formato decimal
     * @param string $numero 
     */
    public static function numeroQuebrado($numero, $casasDecimais = null)
    {
        $numero = self::parse($numero);
        
        return Yii::app()->numberFormatter->format(self::getFormat($casasDecimais), $numero);
    }
    
    /**
     * Converte número no formato de porcentagem
     * @param string $numero 
     */
    public static function porcentagem($numero, $casasDecimais = 0)
    {
        $numero = self::parse($numero);
        $casasDecimais = (int) $casasDecimais;
        
        $format = self::getFormat($casasDecimais);
        
        // Supondo que o % nunca estará enfiado no meio do número
        if (0 === strpos(Yii::app()->locale->getPercentFormat(), '%')) {
            $format = '%' . $format;
        }
        else {
            $format .= '%';
        }
        
        return Yii::app()->numberFormatter->format($format, $numero);
    }
    
    /**
     * Converte uma string i18n em um número do PHP (float)
     * @param string $numero Se for um float ou integer, retornará direto
     * @return float
     */
    public static function parse($numero)
    {
        if (is_string($numero)) {
            
            $numero = str_replace(
                array(
                    Yii::app()->locale->getNumberSymbol('group'),
                    Yii::app()->locale->getNumberSymbol('percentSign'),
                    Yii::app()->locale->getNumberSymbol('decimal'),                    
                ),
                array(
                    '',
                    '',
                    '.',
                ),
                $numero
            );
        }
        
        return (float) $numero;
    }
    
    /**
     * Se não informar as casas decimais, trás o que for padrão do sistema
     * @param integer $casasDecimais
     */
    public static function getFormat($casasDecimais = null)
    {
        $format = Yii::app()->locale->getDecimalFormat();
        
        if (null === $casasDecimais) {
            return $format;
        }
        
        $casasDecimais = (int) $casasDecimais;
        
        // ATENÇÃO!!! O parâmetro abaixo não está "fixando" o uso do ponto
        // Essa string significa pro Yii que naquela posição ele achará o
        // separador de casas decimais.
        $format = explode('.', $format);
        
        if ($casasDecimais < 1) {            
            return array_shift($format);
        }
        
        $format = $format[0] . '.';
        
        return str_pad($format, strlen($format) + $casasDecimais, '#', STR_PAD_RIGHT);
    }
}