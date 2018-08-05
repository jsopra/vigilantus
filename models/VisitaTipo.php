<?php
namespace app\models;

class VisitaTipo
{
	const NORMAL = 1;
	const RECUPERADA = 2;

	public static function getDescricoes()
	{
		return [
			self::NORMAL => 'Normal',
			self::RECUPERADA => 'Recuperada',
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
