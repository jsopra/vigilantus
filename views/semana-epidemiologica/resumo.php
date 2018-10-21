<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Resumo de Trabalho de Campo do agente';
$this->params['breadcrumbs'][] = ['label' => 'Semanas Epidemiológicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visitas de Agentes', 'url' => ['agentes', 'cicloId' => $ciclo->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1 style="margin-bottom: 0;"><?= Html::encode($this->title) ?></h1>
    <h2 style="margin-bottom: 1;"><span style="color: #797979;"><?= Html::encode($ciclo->nome) ?></span> </h2>

</div>

<br />

<?php if ($data === null) : ?>

	<p>Nenhuma informação de visita localizada</p>

<?php else : ?>

	report

<?php endif; ?>