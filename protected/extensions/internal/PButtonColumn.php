<?php

Yii::import('zii.widgets.grid.CButtonColumn');

/**
 * Classe para demarcar as linhas
 */
class PButtonColumn extends CButtonColumn
{
    /**
    * Flag que indica que esta coluna não será congelada
    */
    public $dynamic;
}
