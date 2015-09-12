<?php
namespace app\widgets\wizard;

use yii\base\Widget;

class Wizard extends Widget
{
    /**
     * @var array
     */
    public $tabs;

    /**
     * Parâmetros a adicionar na URL
     * @var array
     **/
    public $params;

    /**
     * @var int
     */
    public $activeTab = 0;

    public function init()
    {
        if(!is_array($this->tabs) || count($this->tabs) < 1) {
            throw new Exception("É preciso definir as abas do Wizard");
        }

        if(!is_numeric($this->activeTab)) {
            throw new Exception("Aba ativa do Wizard está definida incorretamente");
        }

        if(!isset($this->tabs[$this->activeTab])) {
            throw new Exception("Aba ativa do Wizard não existe");
        }
    }

    public function run()
    {
        echo $this->render('tabs', [
            'tabs' => $this->tabs,
            'active' => $this->activeTab,
            'params' => $this->params,
        ]);
    }
}
