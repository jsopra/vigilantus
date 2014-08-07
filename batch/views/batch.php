<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\EspecieTransmissor $model
 */

$this->title = $pageTitle;
$this->params['breadcrumbs'][] = ['label' => 'Listagem', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-columns">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?php
    if (Yii::$app->controller->action->extraFieldsPartial) {

        echo "<hr />\n";

        echo $this->renderPartial(
            Yii::$app->controller->action->extraFieldsPartial,
            ['model' => $model, 'form' => $form]
        );
    }
    ?>
    <hr />
    <p>Associe as colunas da sua planilha com os campos do cadastro:</p>
    <p>
        <div class="btn-group">
          <button type="button" id="preencher-automaticamente" class="btn btn-small btn-flat primary">Associar Automaticamente</button>
          <button type="button" id="limpar-todos" class="btn btn-small btn-flat danger">Limpar Todas</button>
        </div>
    </p>
    <table id="posicoes-colunas" class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th>Coluna</th>
                <th>Campo</th>
                <th>Cabeçalho</th>
                <th>Exemplo</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $position = 0;
        foreach ($exampleRow->data as $column => $example) :
            ?>
            <tr>
                <td><?php echo $position + 1 ?></td>
                <td>
                    <?php
                    echo $form->field(
                        $model,
                        'columns[' . $position . ']',
                        [
                            'inputOptions' => ['role' => 'position'],
                            'template' => '{input}',
                        ]
                    )
                    ->dropDownList(['' => 'Nenhum'] + $model->columnLabels())
                    ;
                    ?>
                </td>
                <td><?php echo $headerRow->data[$column] ?></td>
                <td><?php echo $example ?></td>
                <td role="hint">&nbsp;</td>
            </tr>
            <?php
            $position++;
        endforeach;
        ?>
        </tbody>
    </table>

    <script>
    var columnHints = <?= Json::encode($model->columnHints()) ?>;
    var columnLabels = <?= Json::encode($model->columnLabels()) ?>;
    </script>
    <div class="form-group form-actions">
        <?php
        echo Html::submitButton(
            'Processar Carga',
            ['class' => 'btn btn-flat primary']
        );
        
        echo Html::a(
            'Cancelar',
            [Yii::$app->controller->action->id, 'clear' => 1]
        );

        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>