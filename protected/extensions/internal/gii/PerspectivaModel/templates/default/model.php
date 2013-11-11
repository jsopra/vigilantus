<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * Este é a classe de modelo da tabela "<?php echo $tableName; ?>".
 *
 * Estas são as colunas disponíveis na tabela '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * Estas são as relações do modelo disponíveis:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return <?php echo $modelClass; ?> the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string nome da tabela do banco de dados
	 */
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			// Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array regras de relações
	 */
	public function relations()
	{
		// AVISO: você talvez tenha de ajustar o nome da relação gerada.
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @return array descrição dos atributos (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => Yii::t('$modelClass', '$label'),\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * Retorna uma lista de modelos baseada nas condições de filtro/busca atuais
	 * @return CActiveDataProvider o data provider que pode retornar os dados.
	 */
	public function search()
	{
		// Aviso: Remove do código a seguir os atributos que não deveriam ser
		// pesquisados pelo usuário.

		$criteria=new CDbCriteria;

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Exclui a linha da tabela correspondente a este active record.
	 * @return boolean se a exclusão foi feita com sucesso ou não.
	 * @throws CException se o registro for novo
	 */
	public function delete()
	{
		$transaction = $this->getDbConnection()->beginTransaction();

		try {
			
			// Implemente aqui as exclusões mais complexas
			$return = parent::delete();

			if ($return)
				$transaction->commit();
			else
				$transaction->rollback();
		}
		catch(Exception $e) {

			$transaction->rollback();

			throw $e;
		}

		return $return;
	}
}
