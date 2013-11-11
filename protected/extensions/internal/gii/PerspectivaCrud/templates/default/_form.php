<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('class'=>'well'),
)); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
		<?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php
}

echo "<?php echo CHtml::openTag('div',array('class'=>'form-btns'));

       \$this->widget(
                'bootstrap.widgets.TbButton', 
                array(
                    'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'size'=>'small', // null, 'large', 'small' or 'mini'
                    'buttonType'=>'submit', 
                    'label'=> \$model->isNewRecord ? Yii::t('" . $this->modelClass . "','Cadastrar') : Yii::t('" . $this->modelClass . "','Atualizar')
                )
        ); 
        echo CHtml::link('Cancelar',array('{$this->modelClass}/index'),array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir para a tela de exibição de todos os " . $this->pluralize($this->class2name($this->modelClass)) . "'));

    echo CHtml::closeTag('div');
\n";

echo "\$this->endWidget(); ?>\n"; 

?>