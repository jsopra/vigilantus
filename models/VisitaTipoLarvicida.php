<?php
namespace app\models;

class VisitaTipoLarvicida
{
	const A = 1;
	const B = 2;

	public static function getDescricoes()
	{
		return [
			self::A => 'AL',
			self::B => 'BL',
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
