<?php
namespace app\models;

class VisitaTipoAdulticida
{
	const A = 1;
	const B = 2;

	public static function getDescricoes()
	{
		return [
			self::A => 'A',
			self::B => 'B',
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
