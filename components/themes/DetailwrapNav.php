<?php
namespace app\components\themes;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Widget;

class DetailwrapNav extends Widget
{
	/**
	 * @var array list of items in the nav widget. Each array element represents a single
	 * menu item which can be either a string or an array with the following structure:
	 *
	 * - label: string, required, the nav item label.
	 * - url: optional, the item's URL. Defaults to "#".
	 * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
	 * - linkOptions: array, optional, the HTML attributes of the item's link.
	 * - options: array, optional, the HTML attributes of the item container (LI).
	 * - active: boolean, optional, whether the item should be on active state or not.
	 * - items: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
	 *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
	 *
	 * If a menu item is a string, it will be rendered directly without HTML encoding.
	 */
	public $items = [];
	/**
	 * @var boolean whether the nav items labels should be HTML-encoded.
	 */
	public $encodeLabels = true;
	/**
	 * @var boolean whether to automatically activate items according to whether their route setting
	 * matches the currently requested route.
	 * @see isItemActive
	 */
	public $activateItems = true;
	/**
	 * @var string the route used to determine if a menu item is active or not.
	 * If not set, it will use the route of the current request.
	 * @see params
	 * @see isItemActive
	 */
	public $route;
	/**
	 * @var array the parameters used to determine if a menu item is active or not.
	 * If not set, it will use `$_GET`.
	 * @see route
	 * @see isItemActive
	 */
	public $params;


	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		if ($this->route === null && Yii::$app->controller !== null) {
			$this->route = Yii::$app->controller->getRoute();
		}
		if ($this->params === null) {
			$this->params = $_GET;
		}
		Html::addCssClass($this->options, 'nav');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo $this->renderItems();
	}

	/**
	 * Renders widget items.
	 */
	public function renderItems()
	{
		$items = [];
		foreach ($this->items as $i => $item) {
			if (isset($item['visible']) && !$item['visible']) {
				unset($items[$i]);
				continue;
			}
			$items[] = $this->renderItem($item);
		}

		return Html::tag('ul', implode("\n", $items), $this->options);
	}

	/**
	 * Renders a widget's item.
	 * @param string|array $item the item to render.
	 * @return string the rendering result.
	 * @throws InvalidConfigException
	 */
	public function renderItem($item)
	{
		if (is_string($item)) {
			return $item;
		}
		if (!isset($item['label']) && !isset($item['icon'])) {
			throw new InvalidConfigException("The 'label' or the 'icon' option are required.");
		}
        
        $label = '';
        
        if(isset($item['icon']))
            $label .= ' ' . Html::tag('i', '', ['class' => 'icon-' . $item['icon']]) . ' ';
        
        if(isset($item['label']))
            $label .= $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
		
        $options = ArrayHelper::getValue($item, 'options', []);
		$items = ArrayHelper::getValue($item, 'items');
		$url = Url::toRoute(ArrayHelper::getValue($item, 'url', '#'));
		$linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

		$active = isset($item['active']) ? ArrayHelper::remove($item, 'active', false) : $this->isItemActive($item);
		if ($active)
			Html::addCssClass($options, 'active');

		if ($items !== null) {
			$linkOptions['data-toggle'] = 'dropdown';
			Html::addCssClass($options, 'dropdown');
			Html::addCssClass($linkOptions, 'dropdown-toggle hidden-xs hidden-sm');
			$label .= ' ' . Html::tag('b', '', ['class' => 'caret']);
			if (is_array($items)) {
				$lines = [];
                foreach ($items as $i => $item) {
                    if (isset($item['visible']) && !$item['visible']) {
                        unset($items[$i]);
                        continue;
                    }
                    if (is_string($item)) {
                        $lines[] = $item;
                        continue;
                    }
                    if (!isset($item['label'])) {
                        throw new InvalidConfigException("The 'label' option is required.");
                    }
                    $slabel = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
                    $soptions = ArrayHelper::getValue($item, 'options', []);
                    $slinkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
                    $slinkOptions['tabindex'] = '-1';
                    $scontent = Html::a($slabel, ArrayHelper::getValue($item, 'url', '#'), $slinkOptions);
                    $lines[] = Html::tag('li', $scontent, $soptions);
                }
                
                $ulOptions = [];
                Html::addCssClass($ulOptions, 'dropdown-menu');

                $items = Html::tag('ul', implode("\n", $lines), $ulOptions);
			}
		}

		return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
	}


	/**
	 * Checks whether a menu item is active.
	 * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
	 * When the `url` option of a menu item is specified in terms of an array, its first element is treated
	 * as the route for the item and the rest of the elements are the associated parameters.
	 * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
	 * be considered active.
	 * @param array $item the menu item to be checked
	 * @return boolean whether the menu item is active
	 */
	protected function isItemActive($item)
	{
		if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
			$route = $item['url'][0];
			if ($route[0] !== '/' && Yii::$app->controller) {
				$route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
			}
			if (ltrim($route, '/') !== $this->route) {
				return false;
			}
			unset($item['url']['#']);
			if (count($item['url']) > 1) {
				foreach (array_splice($item['url'], 1) as $name => $value) {
					if (!isset($this->params[$name]) || $this->params[$name] != $value) {
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}
