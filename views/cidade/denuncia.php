<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Bairro;
use yii\helpers\ArrayHelper;

$this->title = 'Faça uma denúncia para Prefeitura Municipal de ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>

<?= $this->render('_cidadeHeader', ['municipio' => $municipio, 'cliente' => $cliente, 'button' => '_buttonMapa']); ?>

<div class="panel panel-default" style="margin-top: 2.5em;">

	<div class="panel-heading denunciar">
		<h4  class="text-center" style="color: #CC0000; font-weight: bold; margin-top: 1em; font-size: 2.5em; margin-top: 0;">Denuncie!</h4>
		<br />
		<p class="text-center" style="line-height: 1.5em; color: #585858; font-size: 1.6em;">
		<font style="font-weight: bold; color: #CC0000; font-size: 1.05em;">Faça sua parte!</font>
		Sua denúncia será avaliada pela <font style="font-weight: bold; font-size: 1.05em; color: #000;">Prefeitura Municipal</font>
		e você receberá acesso para <font style="font-weight: bold; color: #000; font-size: 1.05em;">acompanhar</font> a resolução.
		</p>

		<p class="text-center" style="color: #000; font-size: 1.3em;"><strong>As informações da sua denúncia são mantidas em sigilo</strong></p>
	</div>

	<div class="panel-body">

		<div class="cidade_formDenuncia">

		    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

		        <p style="color: #797979;"><strong>Objeto da denúncia</strong></p>

		        <div class="row">
		            <div class="col-xs-12">
		                <?php
		                $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
		                echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
		                ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-12">
		                <?= $form->field($model, 'endereco') ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-6">
		                <?php
		                echo $form->field($model, 'tipo_imovel')->dropDownList(\app\models\DenunciaTipoImovel::getDescricoes(), ['prompt' => 'Selecione..']);
		                ?>
		            </div>

		            <div class="col-xs-6">
		                <?php
		                $tipos = \app\models\DenunciaTipoProblema::find()->ativos()->orderBy('nome')->all();
		                echo $form->field($model, 'denuncia_tipo_problema_id')->dropDownList(ArrayHelper::map($tipos, 'id', 'nome'), ['prompt' => 'Selecione..']);
		                ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-12">
		                <?= $form->field($model, 'pontos_referencia') ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-12">
		                <?= $form->field($model, 'mensagem')->textArea() ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-12">
		                <?= $form->field($model, 'file')->fileInput() ?>
		            </div>
		        </div>

		        <hr />

		        <p style="color: #797979;"><strong>Seus dados (opcional)</strong></p>

		        <div class="row">
		            <div class="col-xs-12">
		                <?= $form->field($model, 'nome')->textInput() ?>
		            </div>
		        </div>

		        <div class="row">
		            <div class="col-xs-7">
		                <?= $form->field($model, 'email')->textInput() ?>
		            </div>

		            <div class="col-xs-5">
		                <?= Html::activeLabel($model, 'telefone') ?>
		                <?php
		                echo MaskedInput::widget([
		                    'model' => $model,
		                    'name' => 'telefone',
		                    'mask' => '(99) 9999-9999',
		                ]);
		                ?>
		            </div>
		        </div>

		        <div class="form-group">
		            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
		        </div>

		    <?php ActiveForm::end(); ?>

		</div>
	</div>

</div>
