<?php
namespace app\components\themes;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class DetailwrapNavBar extends Widget
{
	/**
	 * @var string the text of the brand. Note that this is not HTML-encoded.
	 * @see http://twitter.github.io/bootstrap/components.html#navbar
	 */
	public $brandLabel;
	/**
	 * @param array|string $url the URL for the brand's hyperlink tag. This parameter will be processed by [[Url::toRoute()]]
	 * and will be used for the "href" attribute of the brand link. Defaults to site root.
	 */
	public $brandUrl = '/';
	/**
	 * @var array the HTML attributes of the brand link.
	 */
	public $brandOptions = [];
	/**
	 * @var string text to show for screen readers for the button to toggle the navbar.
	 */
	public $screenReaderToggleText = 'Toggle navigation';
	/**
	 * @var bool whether the navbar content should be included in a `container` div which adds left and right padding.
	 * Set this to false for a 100% width navbar.
	 */
	public $padded = true;
    
    public $municipios;
    public $municipioLogado;

	/**
	 * Initializes the widget.W
	 */
	public function init()
	{
		parent::init();
		$this->clientOptions = false;
		Html::addCssClass($this->options, 'navbar');
        Html::addCssClass($this->options, 'navbar-inverse');
        
		$this->options['role'] = 'banner';
		
		echo Html::beginTag('header', $this->options);

		echo Html::beginTag('div', ['class' => 'navbar-header']);
		
        echo $this->renderToggleButton();
		
        if ($this->brandLabel !== null)  {
			Html::addCssClass($this->brandOptions, 'navbar-brand');
            echo Html::a($this->brandLabel, $this->brandUrl, $this->brandOptions);
        }
        
        $qtdeMunicipios = count($this->municipios);
        if($qtdeMunicipios > 0) {

            echo Html::beginTag('div', ['class' => 'navbar-municipio']);

            $listMunicipios = [];
            foreach ($this->municipios as $object)
                $listMunicipios[Url::toRoute(['site/session', 'id' => $object->id])] = $object->nome . '/' . $object->sigla_estado;

            if($qtdeMunicipios == 1) {
                echo '<p class="unico-municipio">' . $this->municipios[0]->nome . '/' . $this->municipios[0]->sigla_estado . '</p>';
            }
            else if(is_object($this->municipioLogado)) {
                echo Html::dropDownList('user_municipio', Url::toRoute(['/site/session', 'id' => $this->municipioLogado->id]), $listMunicipios);
            }
            
            echo Html::endTag('div');
        }
        
		echo Html::endTag('div');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo Html::endTag('header');
	}

	/**
	 * Renders collapsible toggle button.
	 * @return string the rendering toggle button.
	 */
	protected function renderToggleButton()
	{
		$bar = Html::tag('span', '', ['class' => 'icon-bar']);
		$screenReader = '<span class="sr-only">'.$this->screenReaderToggleText.'</span>';
		return Html::button("{$screenReader}\n{$bar}\n{$bar}\n{$bar}", [
            'id' => 'menu-toggler',
			'class' => 'navbar-toggle',
			'data-toggle' => 'collapse',
		]);
	}
}
