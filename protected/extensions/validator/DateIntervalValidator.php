<?php
/**
 * Valida intervalos entre datas
 */
class DateIntervalValidator extends DateValidator
{
	/**
	 * @var string intervalo mínimo
	 */
	public $min;

	/**
	 * @var string intervalo máximo
	 */
	public $max;

	/**
	 * @var integer unidade do intervalo mínimo de datas
	 */
	public $minIntervalStep = 'days';

	/**
	 * @var integer unidade do intervalo máximo de datas
	 */
	public $maxIntervalStep = 'days';

	/**
	 * @var integer intervalo máximo entre as datas em "steps" (dia, segundos, semanas, etc.)
	 */
	public $maxInterval;

	/**
	 * @var integer intervalo mínimo entre as datas em "steps" (dia, segundos, semanas, etc.)
	 */
	public $minInterval;

	/**
	 * @var string atributo da data final
	 */
	public $end;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object, $attribute)
	{
		// Valida o formato de data
		parent::validateAttribute($object, $attribute);

		if ($this->end) {
			parent::validateAttribute($object, $this->end);
		}

		if (!$object->getErrors($attribute) && (!$this->end || !$object->getErrors($this->end) )) {

			if ($this->min || $this->max || $this->minInterval || $this->maxInterval) {

				$end_attribute = $this->end;

				$dataInicial = $object->$attribute;
				$timestampInicial = Date::getTimestamp($object->$attribute, $this->format);

				$dataFinal = $timestampFinal = null;

				// Se tem o atributo da data final
				if ($end_attribute) {

					if (!isset($object->$end_attribute)) {

						throw new Exception('O modelo ' . get_class($object) . ' não possui o atributo ' . $end_attribute);
					}

					$timestampFinal = Date::getTimestamp($object->$end_attribute, $this->format);
					$dataFinal      = $object->$end_attribute;
				}
				// Não tem data final, mas valida um intervalo
				else if ($this->minInterval || $this->maxInterval) {
					throw new Exception('Para usar validação de intervalos de data informe o atributo da data final como "end"');
				}

				// Se tem uma data inicial e final, não deixa a segunda ser menor que a primeira
				if ($timestampFinal && $timestampFinal < $timestampInicial) {

					$this->addError($object,$attribute,Yii::t('Validator','{attribute} não pode ser superior a {date}.',array('{date}' => $dataFinal)));
				}

				// Data mínima
				if ($this->min) {

					$dataLimite = $this->min;
					$timestampLimite = Date::getTimestamp($this->min, $this->format);

					if ($timestampInicial <= $timestampLimite) {
						$this->addError($object,$attribute,Yii::t('Validator','{attribute} não pode ser inferior a {date}.',array('{date}' => $dataLimite)));
					}

					if ($timestampFinal && $timestampFinal <= $timestampLimite) {
						$this->addError($object,$end_attribute,Yii::t('Validator','{attribute} não pode ser inferior a {date}.',array('{date}' => $dataLimite)));
					}
				}

				// Data máxima
				if ($this->max) {

					$dataLimite = $this->max;
					$timestampLimite = Date::getTimestamp($this->max, $this->format);

					if ($timestampInicial >= $timestampLimite) {
						$this->addError($object,$attribute,Yii::t('Validator','{attribute} não pode ser superior a {date}.',array('{date}' => $dataLimite)));
					}

					if ($timestampFinal && $timestampFinal >= $timestampLimite) {
						$this->addError($object,$end_attribute,Yii::t('Validator','{attribute} não pode ser superior a {date}.',array('{date}' => $dataLimite)));
					}
				}

				// Intervalo (minimo ou maximo)
				$testesIntervalo = array('min', 'max');

				foreach ($testesIntervalo as $dir) {

					// Não foi setado o intervalo? Ignora a validação
					if (
						($dir == 'min' && !$this->minInterval)
						||
						($dir == 'max' && !$this->maxInterval)
						) {
						continue;
					}

					$timestampLimite = $erro = null;

					$hourStep = $minuteStep = $secondStep = $dayStep = $monthStep = $yearStep = 0;

					$intervalStep = ($dir == 'min') ? $this->minIntervalStep : $this->maxIntervalStep;
					$interval     = ($dir == 'min') ? $this->minInterval     : $this->maxInterval;
					$descricao    = ($dir == 'min') ? 'inferior'             : 'superior';
					$interval     = abs(intval($interval));

					if ($intervalStep == 'days') {
						$dayStep = $interval;
						$erro = 'dias';
					}
					elseif ($intervalStep == 'weeks') {
						$dayStep = ($interval * 7);
						$erro = 'semanas';
					}
					elseif ($intervalStep == 'months') {
						$monthStep = $interval;
						$erro = 'meses';
					}
					elseif ($intervalStep == 'years') {
						$yearStep = $interval;
						$erro = 'anos';
					}
					elseif ($intervalStep == 'hours') {
						$hourStep = $interval;
						$erro = 'horas';
					}
					elseif ($intervalStep == 'minutes') {
						$minuteStep = $interval;
						$erro = 'minutos';
					}
					elseif ($intervalStep == 'seconds') {
						$secondStep = $interval;
						$erro = 'segundos';
					}
					else {
						throw new Exception('A unidade de medida de datas ' . $intervalStep . ' não foi reconhecida');
					}

					$timestampLimite = mktime(
						date('H', $timestampInicial) + $hourStep,
						date('i', $timestampInicial) + $minuteStep,
						date('s', $timestampInicial) + $secondStep,
						date('m', $timestampInicial) + $monthStep,
						date('d', $timestampInicial) + $dayStep,
						date('Y', $timestampInicial) + $yearStep
					);

					// Se estourou o limite 
					if (
						($dir == 'max' && $timestampFinal > $timestampLimite)
						||
						($dir == 'min' && $timestampFinal < $timestampLimite)
						) {

						$this->addError(
							$object,
							$end_attribute,
							Yii::t(
								'Validator',
								'A diferença entre as datas não pode ser ' . $descricao . ' a {interval} ' . $erro,
								array('{interval}' => $interval)
							)
						);
					}
				}
			}			
		}
	}
}
