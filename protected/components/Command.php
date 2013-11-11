<?php
/**
 * FeedhitsCommand gera ações de processamento para o Feedhits
 *
 */
class Command extends CConsoleCommand 
{
	/**
	 * Servidor do processamento
	 * @var Servidor 
	 */
	protected static $_server;
	
	/**
	 * Construtor das actions
	 * 
	 * @param string $server
	 * @return void 
	 */
	protected static function _init($server)
	{
		self::$_server = null;
		
		if(strstr($server,'.feedhits.com.br') === false) {
			Yii::log('Servidor não especificado corretamente para Send Messages: ' . $server, CLogger::LEVEL_ERROR);
			Yii::app()->end();
		}
		
		self::$_server = Servidor::getByHost($server);
		
		if(!self::$_server instanceof Servidor) {
			Yii::log('Servidor não especificado corretamente para Send Messages: ' . $server, CLogger::LEVEL_ERROR);
			Yii::app()->end();
		}

		return;
	}
}