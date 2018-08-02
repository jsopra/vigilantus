<?php
namespace app\models;

class VisitaStatus
{
	const AGENDADA = 1;
	const CONCLUIDA = 2;
	const NAO_CONCLUIDA = 3;

	public static function getDescricoes()
	{
		return [
			self::AGENDADA => 'Agendada',
			self::CONCLUIDA => 'Concluída',
			self::NAO_CONCLUIDA => 'Não Concluída',
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
