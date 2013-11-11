<?php
/**
 * CNPJValidator class file.
 *
 * @author Rodrigo S Nurmberg <rsn86@rsn86.com>
 * @link http://twitter.com/rsn1986/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CNPJValidator valida um CNPJ brasileiro conforme algoritimo de geração.
 * @author Rodrigo S Nurmberg <rsn86@rsn86.com>
 * @version 0.1
 */
class CNPJ extends CValidator
{
	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the data object being validated
	 * @param string the name of the attribute to be validated.
	 */
	protected function validateAttribute( $object, $attribute ){
		if ($object->tipo_documento_id == 1) {
		  if ( !$this->valida( $object->$attribute ) )
             $this->addError($object, $attribute, Yii::t('yii','{attribute} não é um CNPJ válido.'));
    }
	}
    
    public function clientValidateAttribute($object,$attribute) {

    }

    
    /*
     * @autor: Rodrigo S Nurmberg
     * @email: rsn86@rsn86.com
    */
    
    // Função que valida o CNPJ
    public function valida($cnpj)
    {	// Verifiva se o número digitado contém todos os digitos
        $cnpj = str_pad(preg_replace('/[^0-9_]/', '', $cnpj), 14, '0', STR_PAD_LEFT);

        // valida número sequencial 1111... 22222 ......
        for ($x=0; $x<10; $x++)
            if ( $cnpj == str_repeat($x, 14) )
                return false;

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if ( strlen($cnpj) != 14 ) {
            return false;
        } else {   // Calcula os números para verificar se o CNPJ é verdadeiro
            for ($t = 12; $t < 14; $t++) {
				$d = 0;
				$c = 0;
                for ($m = $t - 7; $m >= 2; $m--, $c++) {
                    $d += $cnpj{$c} * $m;
                }
				for ($m = 9; $m >= 2; $m--, $c++) {
                    $d += $cnpj{$c} * $m;
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cnpj{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }

}