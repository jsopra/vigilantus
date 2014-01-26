<?php
use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bairro-tipo-form">
    
	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome')) ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'categoria_id')->dropDownList(\app\models\BairroCategoria::listData('nome')) ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'bairro_quarteirao_id')->dropDownList(BairroQuarteirao::listData('numero_quarteirao')) ?>
        </div>
        <div class="col-xs-1">
            <?= $form->field($model, 'seq')->textInput() ?>
        </div>
        
        <div class="col-xs-2 col-lg-offset-1">
            <?= $form->field($model, 'folha')->textInput() ?>
        </div>
        <div class="col-xs-1">
            <?= $form->field($model, 'mes')->textInput() ?>
        </div>
        <div class="col-xs-1">
            <?= $form->field($model, 'ano')->textInput() ?>
        </div>
    </div>
    
    <div class="row">
        <table class="table table-hover">
            <thread>
                <tr>
                    <th class="col-md-2">Rua/Logradouro</th>
                    <th class="col-md-1">Nº</th>
                    <th class="col-md-1">Seq</th>
                    <th class="col-md-2">Complemento</th>
                    <th class="col-md-2">Tipo de Imóvel</th>
                    <th class="col-md-2">Condição de Imóvel</th>
                    <th class="col-md-1">Existe foco?</th>
                    <th class="col-md-1">&nbsp;</th>
                </tr>
            </thread>
            <tbody>
                <tr class="first">
                    <td>Rua/Logradouro</td>
                    <td>Nº</td>
                    <td>Seq</td>
                    <td>Complemento</td>
                    <td>Tipo de Imóvel</td>
                    <td>Condição de Imóvel</td>
                    <td>Existe foco?</td>
                    <td><i class="icon-ok-sign"></i></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group vigilantus-form">
        <?php
        echo Html::submitButton(
            $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
            ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
        );

        echo Html::a(
            'Cancelar',
            array('/bairro-quarteirao/index'),
            array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir à lista de boletins cadastrados')
        );

        ?>

    </div>

	<?php ActiveForm::end(); ?>

</div>
