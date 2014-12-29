<?= '<?php'; ?>

use yii\helpers\Html;

$this->title = "<?= $generator->getControllerID() . '/' . $action; ?>";
$this->params['breadcrumbs'][] = $this->title;
<?= '?>'; ?>

<h1><?= '<?= Html::encode($this->title); ?>'; ?></h1>
<p>Conteúdo...</p>
