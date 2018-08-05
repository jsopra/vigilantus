<?php
namespace app\models;

class VisitaPendencia
{
	const FECHADA = 1;

	public static function getDescricoes()
	{
		return [
			self::FECHADA => 'Fechada',
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
