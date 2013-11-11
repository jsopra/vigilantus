<?php
/**
 * Estende o CDateValidator com MIN e MAX
 */
class DateValidator extends CDateValidator
{
	/** 
	 * Por padrão o formato de Date::getDateFormat()
	 */
	public $format = false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		// Não informou o formato? Usa o padrão do sistema
		if (!$this->format) {
			$this->format = Date::getDateFormat();
		}

		return parent::validateAttribute($object,$attribute);
	}
}
