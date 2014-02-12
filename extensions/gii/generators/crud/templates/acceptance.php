<?= '<?php'; ?>

<?php 
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$acceptanceClass = Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)));
?>

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD <?= $acceptanceClass; ?> funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('XXX Item de menu do CRUD'); //@todo

$eu->espero('cadastrar um <?= $acceptanceClass; ?>');
$eu->clico('Cadastrar XXX Item do CRUD'); //@todo
$eu->vejoNoTitulo('Cadastrar XXX Titulo'); //@todo
//@TODO preencher form
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um <?= $acceptanceClass; ?>');
$eu->clicoNoGrid('XXX Registro', 'Atualizar'); //@todo
$eu->vejoNoTitulo('Atualizar XXX Titulo'); //@todo
//@TODO preencher form
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um <?= $acceptanceClass; ?>');
$eu->clicoNoGrid('XXX Registro', 'Excluir'); //@todo
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('XXX Registro'); //@todo