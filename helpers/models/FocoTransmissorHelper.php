<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\FocoTransmissor;

class FocoTransmissorHelper extends YiiStringHelper
{
	public static $meses = [
		1 => 'Janeiro', 
		2 => 'Fevereiro', 
		3 => 'Março', 
		4 => 'Abril', 
		5 => 'Maio', 
		6 => 'Junho', 
		7 => 'Julho', 
		8 => 'Agosto', 
		9 => 'Setembro', 
		10 => 'Outubro', 
		11 => 'Novembro', 
		12 => 'Dezembro'
	];

	/**
	 * Busca mês em extenso para data
	 * @param string $data
	 * @return string|null
	 */
	public static function getMes($data) 
	{
		list(,$mes,) = explode('-', $data);

		return isset(self::$meses[intval($mes)]) ? self::$meses[intval($mes)] : null; 
	}

	/**
	 * Resumo descritivo baseado em tipos de espécie
	 * @param FocoTransmissor $foco
	 * @return string
	 */
	public static function getForma(FocoTransmissor $foco) 
	{
		$html = [];

		if($foco->quantidade_ovos > 0) {
			$html[] = 'Ovo';
		}

		if($foco->quantidade_forma_aquatica > 0) {
			$html[] = 'L';
		}

		if($foco->quantidade_forma_adulta > 0) {
			$html[] = 'A';
		}

		return implode('/', $html);
	}
}