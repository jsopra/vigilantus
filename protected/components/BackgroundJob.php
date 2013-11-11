<?php

class BackgroundJob {

	const LOW = 0;
	const NORMAL = 1;
	const HIGH = 2;

	/**
	 * Coloca uma tarefa de uma classe para rodar em background
	 * 
	 * Exemplo: BackgroundJob::client('EnviaEmailJob', array('assunto' => 'Gearman', 'email' => 'rob@erval.com' ), BackgroundJob::HIGH);
	 * 
	 * Ao processar pelo worker, ele vai executar: 
	 * 
	 * EnviarEmailJob::processar(array('assunto' => 'Gearman', 'email' => 'rob@erval.com' ));
	 * 
	 * @param type $classe
	 * @param type $params
	 * @param type $priority
	 * @return true
	 */
	public static function client($classe, $params = null, $priority = self::NORMAL) {

		switch ($priority) {
			case self::HIGH:
				Yii::app()->gearman->client()->doHighBackground(self::path(), serialize(array('classe' => $classe, 'params' => $params))
				);
				break;
			case self::LOW:
				Yii::app()->gearman->client()->doLowBackground(self::path(), serialize(array('classe' => $classe, 'params' => $params))
				);
				break;
			default:
				Yii::app()->gearman->client()->doBackground(self::path(), serialize(array('classe' => $classe, 'params' => $params))
				);
		}
		return true;
	}

	/*
	 * Retorna o worker que realiza os trabalhos em background
	 */
	public static function worker() {
		$worker = Yii::app()->gearman->worker();
		$worker->addFunction(self::path(), array('BackgroundJob', 'processar'));
		return $worker;
	}

	/*
	 * Retorna o worker que limpa a fila e apaga todas as pendencias
	 */
	public static function workerClearQueue() {
		$oGearman = new Gearman();
		$worker = $oGearman->worker();
		$worker->addFunction(self::path(), array('BackgroundJob', 'fazNada'));
		return $worker;
	}

	/*
	 * Recebe um job, nao processa e devolve para que saia da fila do Gearman.
	 */
	public static function fazNada($job) {
		$atributos = unserialize($job->workload());
		Yii::log("Descartado Job (id " . $job->unique() . "): " . $atributos['classe'] . " com parametros " . print_r($atributos['params'], true), 'info', 'gearman.processar');
		return true;
	}

	/*
	 * Processa um job
	 */
	public static function processar($job) {
		$atributos = unserialize($job->workload());
		echo '[' . date('d/m/Y h:i:s') . "] Rodando " . serialize($atributos) . "\n";
		$nome_da_classe = $atributos['classe'];
		$model = new $nome_da_classe;
		$model->processar($atributos['params']);
		echo '[' . date('d/m/Y h:i:s') . "] Rodou \n";
		return true;
	}

	public static function path() {
		return 'social-manager-cli';
	}

}
