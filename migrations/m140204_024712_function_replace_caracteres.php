<?php

use yii\db\Schema;

class m140204_024712_function_replace_caracteres extends \yii\db\Migration
{
	public function up()
	{
		$this->db->pdo->query('
			CREATE OR REPLACE FUNCTION unaccent(text)
			RETURNS text AS
			$BODY$
			SELECT translate(
					$1,
					\'áàãâäéèëêíìïóòõôöúùûüñçÁÀÃÂÄÉÈËÊÍÌÏÓÒÕÔÖÚÙÛÜÑÇ\',
					\'aaaaaeeeeiiiooooouuuuncAAAAAEEEEIIIOOOOOUUUUNC\'
					);
			$BODY$
			LANGUAGE sql IMMUTABLE STRICT
			COST 100;
		');
	}

	public function down()
	{
		echo "m140204_024712_function_replace_caracteres cannot be reverted.\n";
		return false;
	}
}
