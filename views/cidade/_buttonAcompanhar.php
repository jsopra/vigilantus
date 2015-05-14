<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<form class="form-inline" action="/cidade/acompanhar-denuncia" method="get">
    <input type="hidden" name="id" value="<?= $cliente->id; ?>" />
    <input type="text" class="form-control" name="hash" placeholder="Nº Protocolo da Denúncia" />
    <button id="enviar" class="btn btn-flat info">Acompanhar</button>
</form>

<br />

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
