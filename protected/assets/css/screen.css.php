<?php

header('Content-Type: text/css; charset=utf-8');

/**
 * Junta todos os frameworks de CSS e estilos separados em um único arquivo.
 * TODO Minimizar arquivo
 */

require __DIR__ . '/main.css';

?>

@media print {

<?php require __DIR__ . '/common/print.css'; ?>

}