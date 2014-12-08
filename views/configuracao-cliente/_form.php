<?php
use app\models\ConfiguracaoCliente;
use app\models\ConfiguracaoTipo;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="configuracao-cliente-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?php
                switch($model->configuracao->tipo) {

                    case ConfiguracaoTipo::TIPO_STRING:
                    case ConfiguracaoTipo::TIPO_INTEIRO:
                    case ConfiguracaoTipo::TIPO_DECIMAL:
                    case ConfiguracaoTipo::TIPO_TIME : {

                        echo $form->field($model, 'valor')->textInput();
                        break;
                    }

                    case ConfiguracaoTipo::TIPO_BOLEANO : {
                        echo $form->field($model, 'valor')->checkBox();
                        break;
                    }

                    case ConfiguracaoTipo::TIPO_RANGE : {
                        echo $form->field($model, 'valor')->dropDownList(['' => 'Selecione...'] + unserialize($model->configuracao->valores_possiveis));
                        break;
                    }
                }
                ?>
            </div>
        </div>
        <div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );

            echo Html::a(
                'Cancelar',
                array('/configuracao-cliente/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Configurações')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
