<?php
namespace app\models;

class DenunciaHistoricoTipo
{
	const INCLUSAO = 1;
	const APROVACAO = 2;
	const REPROVACAO = 3;
	
	public static function getDescricoes()
	{
		return [
			self::INCLUSAO => 'Inclusão', 
			self::APROVACAO => 'Aprovação',
			self::REPROVACAO => 'Reprovação',
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