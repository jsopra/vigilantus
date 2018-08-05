<?php
namespace app\models;

class VisitaAtividade
{
	const RA = 1;
	const LIT = 2;
	const PE = 3;
	const T = 4;
	const DF = 5;
	const PVE = 6;
	const ID = 7;
	const BT = 8;

	public static function getDescricoes()
	{
		return [
			self::RA => 'RA - Revisão de área',
			self::LIT => 'LI+T - Levantamento de índice + Tratamento',
			self::PE => 'PE - Ponto Estratégico',
			self::T => 'T - Tratamento',
			self::DF => 'DF - Delimitação de Foco',
			self::PVE => 'PVE - Pesquisa Vetorial Especial',
			self::ID => 'ID - Investigação de Denúncia',
			self::BT => 'BT - Bloqueio de Transmissão'
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
