<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<form class="form-inline" action="/cidade/acompanhar-ocorrencia" method="get">
    <div class="form-group">
        <input type="hidden" name="id" value="<?= $cliente->id; ?>" />
        <input type="text" class="form-control" name="hash" placeholder="Nº do protocolo da ocorrência" />
    <div class="form-group">
    </div>
        <button id="enviar" class="btn btn-flat info">Acompanhar</button>
    </div>
</form>

<br class="" />

<?php
$view = Yii::$app->getView();
$script = '
    $(document).ready(function(){
        $("#enviar").click(function(){
            if($("input[name=hash]").val() == "") {
                $("input[name=hash]").focus();
                return false;
            } else {
                $("form").submit();
            }
        });
    });
';

$view->registerJs($script);
