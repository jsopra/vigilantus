<?php
namespace app\models;

class OcorrenciaTipoLocalizacao
{
	const INTERIOR = 1;
	const EXTERIOR = 2;

	public static function getDescricoes()
	{
		return [
			self::INTERIOR => 'Interior',
			self::EXTERIOR => 'Exterior',
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
