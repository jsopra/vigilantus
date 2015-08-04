<?php
namespace app\models;

class OcorrenciaStatus
{
	const AVALIACAO = 1;
	const APROVADA = 2;
	const NAO_PROCEDENTE = 3;
	const EXTREVIADA = 4;
	const NAO_ENCONTRADO = 5;
	const SOLICIONADO = 6;
	const ABERTO_TERMO_RESPONSABILIDADE = 7;
	const ENCAMINHADO_FISCALIZACAO_URBANA = 8;
	const ENCAMINHADO_FISCALIZACAO_SANITARIA = 9;
	const FECHADO = 10;
	const AUTO_INTIMACAO = 11;
	const ENCAMINHADO_PARA_SERVICO_URBANO = 12;
	const REPROVADA = 13;
	const AGRICULTURA = 14;

	public static function getDescricoes()
	{
		return [
			self::AVALIACAO => 'Em Avaliação',
			self::APROVADA => 'Aprovada',
			self::NAO_PROCEDENTE => 'Não Procedente',
			self::EXTREVIADA => 'Extraviada',
			self::NAO_ENCONTRADO => 'Não encontrado',
			self::SOLICIONADO => 'Solucionado',
			self::ABERTO_TERMO_RESPONSABILIDADE => 'Aberto TR',
			self::ENCAMINHADO_FISCALIZACAO_URBANA => 'Enc. para fiscalização urbana',
			self::ENCAMINHADO_FISCALIZACAO_SANITARIA => 'Enc. para fiscalização sanitária',
			self::FECHADO => 'Fechado',
			self::AUTO_INTIMACAO => 'Auto de intimação',
			self::ENCAMINHADO_PARA_SERVICO_URBANO => 'Enc. para Serv. Urb.',
			self::REPROVADA => 'Reprovada',
			self::AGRICULTURA => 'Agricultura',
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

	public static function getStatusTerminativos()
	{
		return [
			self::NAO_PROCEDENTE,
			self::EXTREVIADA,
			self::NAO_ENCONTRADO,
			self::SOLICIONADO,
			self::FECHADO,
			self::REPROVADA,
			self::ENCAMINHADO_FISCALIZACAO_URBANA,
			self::ENCAMINHADO_FISCALIZACAO_SANITARIA,
			self::ENCAMINHADO_PARA_SERVICO_URBANO,
			self::AGRICULTURA,
			self::ABERTO_TERMO_RESPONSABILIDADE,
		];
	}

	public static function isStatusTerminativo($idStatus)
	{
		$status = self::getStatusTerminativos();
		return in_array($idStatus, $status);
	}

	public static function getStatusPossiveis($idStatus)
	{
		$status = [];

		switch($idStatus)
		{
			case self::APROVADA :
			case self::AUTO_INTIMACAO :
			{
				$status = [
					self::ABERTO_TERMO_RESPONSABILIDADE => self::getDescricao(self::ABERTO_TERMO_RESPONSABILIDADE),
					self::ENCAMINHADO_FISCALIZACAO_URBANA => self::getDescricao(self::ENCAMINHADO_FISCALIZACAO_URBANA),
					self::ENCAMINHADO_FISCALIZACAO_SANITARIA => self::getDescricao(self::ENCAMINHADO_FISCALIZACAO_SANITARIA),
					self::AUTO_INTIMACAO => self::getDescricao(self::AUTO_INTIMACAO),
					self::ENCAMINHADO_PARA_SERVICO_URBANO => self::getDescricao(self::ENCAMINHADO_PARA_SERVICO_URBANO),
					self::FECHADO => self::getDescricao(self::FECHADO),
					self::EXTREVIADA => self::getDescricao(self::EXTREVIADA),
					self::NAO_ENCONTRADO => self::getDescricao(self::NAO_ENCONTRADO),
					self::SOLICIONADO => self::getDescricao(self::SOLICIONADO),
					self::AGRICULTURA => self::getDescricao(self::AGRICULTURA),
				];
				break;
			}

			default : {
				break;
			}
		}

		return $status;
	}

	public function getStatusFechamento()
	{
		return [
			'aberta' => 'Aberta',
			'fechada' => 'Fechada',
		];
	}

	public function getDescricaoFechamento($id)
	{
		$statusTerminativo = self::isStatusTerminativo($id);

		if($statusTerminativo) {
			return 'Fechada';
		}

		return 'Aberta';
	}
}
