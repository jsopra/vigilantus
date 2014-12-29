<?php
namespace app\models;

class DenunciaTipoImovel
{
	const CASA = 1;
	const APARTAMENTO = 2;
	const TERRENO = 3;
	const EDIFICIO_PUBLICO = 4;
	const ESPACO_COMERCIAL = 5;
	const JARDIM_PRACA = 6;
	const RUA = 7;
	const OUTRO = 8;
	
	public static function getDescricoes()
	{
		return [
			self::CASA => 'Casa', 
			self::APARTAMENTO => 'Apartamento',
			self::TERRENO => 'Terreno',
			self::EDIFICIO_PUBLICO => 'Edifício Público',
			self::ESPACO_COMERCIAL => 'Edifício Comercial',
			self::JARDIM_PRACA => 'Jardim/Praça',
			self::RUA => 'Rua',
			self::OUTRO => 'Outro',
		];
	}

	public static function getDescricao($id)
	{
		$descricoes = self::getDescricoes();
		return isset($descricoes[$id]) ? $descricoes[$id] : null;
	}

	public static function getIDs()
	{
		return array_keys(self::getDescricoes());
	}
}