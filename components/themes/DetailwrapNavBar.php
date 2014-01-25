<?php
namespace app\components\themes;

use yii\bootstrap\Widget;
use yii\helpers\Html;

class DetailwrapNavBar extends Widget
{
	/**
	 * @var string the text of the brand. Note that this is not HTML-encoded.
	 * @see http://twitter.github.io/bootstrap/components.html#navbar
	 */
	public $brandLabel;
	/**
	 * @param array|string $url the URL for the brand's hyperlink tag. This parameter will be processed by [[Html::url()]]
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
