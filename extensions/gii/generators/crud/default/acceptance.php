<?= '<?php'; ?>

use \Phactory;

<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$acceptanceClass = Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)));
?>

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu = new TesterDeAceitacao($scenario);
$eu->quero('verificar que o CRUD <?= $acceptanceClass; ?> funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('XXX Item de menu do CRUD'); //@todo

$eu->espero('cadastrar um <?= $acceptanceClass; ?>');
$eu->clico('Cadastrar XXX Item do CRUD'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um <?= $acceptanceClass; ?>');
$eu->clicoNoGrid('XXX Registro', 'Alterar'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Atualizar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um <?= $acceptanceClass; ?>');
$eu->clicoNoGrid('XXX Registro', 'Excluir'); //@todo
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('XXX Registro'); //@todo
