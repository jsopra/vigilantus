<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name=>label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name=>relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "<?= $tableName ?>".
 *
 * Estas são as colunas disponíveis na tabela "<?= $tableName ?>":
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends ActiveRecord <?= "\n" ?>
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '<?= $tableName ?>';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [<?= "\n\t\t\t" . implode(",\n\t\t\t", $rules) . "\n\t\t" ?>];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
<?php foreach ($labels as $name => $label): ?>
			<?= "'$name' => '" . addslashes($label) . "',\n" ?>
<?php endforeach; ?>
		];
	}
<?php foreach ($relations as $name => $relation): ?>

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function get<?= $name ?>()
	{
		<?= $relation[0] . "\n" ?>
	}
<?php endforeach; ?>
    
    /**
     * Salva atributos do objeto
     * @return boolean
     * @throws CException se o registro for novo
     */
    public function save($runValidation = true, $attributes = null) {

        $currentTransaction = $this->getDb()->getTransaction();		
		$newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();
        
        try {
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {
                
                if($newTransaction)
                    $transaction->commit();
            } 
            else {
            
                if($newTransaction)
                    $transaction->rollback();
            }
        } 
        catch (\Exception $e) {
        
            if($newTransaction)
                $transaction->rollback();
                
            throw $e;
        }

        return $result;
    }
    
    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        $currentTransaction = $this->getDb()->getTransaction();		
		$newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();
        
        try {
            
            $return = parent::delete();
            
            if ($result) {
                
                if($newTransaction)
                    $transaction->commit();
            } 
            else {
            
                if($newTransaction)
                    $transaction->rollback();
            }
        } 
        catch (\Exception $e) {
        
            if($newTransaction)
                $transaction->rollback();
                
            throw $e;
        }

        return $result;
    }
}
