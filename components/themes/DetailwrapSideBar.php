<?php
namespace app\components\themes;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Widget;

class DetailwrapSideBar extends Widget
{
    
    public $items = [];

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
	 * Initializes the widget.W
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
        
		echo Html::beginTag('div', ['id' => 'sidebar-nav']);
	}
    
    /**
	 * Renders the widget.
	 */
	public function run()
	{
		echo $this->renderItems();
        
        echo Html::endTag('div');
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
		if (is_string($item))
			return $item;

		if (!isset($item['label']))
			throw new InvalidConfigException("The 'label' or the 'icon' option are required.");
        
        $label = '';
        
        if(isset($item['icon'])) {
            $label .= ' ' . Html::tag('i', '', ['class' => 'icon-' . $item['icon']]) . ' ';         
            $label .= Html::tag('span', $item['label']);
        }
        else {
            $label .=  $item['label'];
        }
        
        $options = ArrayHelper::getValue($item, 'options', []);
		$items = ArrayHelper::getValue($item, 'items');
		$url = Html::url(ArrayHelper::getValue($item, 'url', '#'));
		$linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        $activeHtml = '';
		$active = isset($item['active']) ? ArrayHelper::remove($item, 'active', false) : $this->isItemActive($item);
		if ($active) {
			Html::addCssClass($options, 'active');
            $activeHtml = '
                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>
            ';
        }

		if ($items !== null) {
            
            Html::addCssClass($linkOptions, 'dropdown-toggle');
            
			$label .= ' ' . Html::tag('i', '', ['class' => 'icon-chevron-down']);
            
			if (is_array($items)) {
				$lines = [];
                
                $ulOptions = [];
                
                foreach ($items as $i => $item) {
                    
                    if (isset($item['visible']) && !$item['visible']) {
                        unset($items[$i]);
                        continue;
                    }
                    
                    if (is_string($item)) {
                        $lines[] = $item;
                        continue;
                    }
                    
                    $slinkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
                    
                    if($this->isItemActive($item)) { 
                        Html::addCssClass($ulOptions, 'active');
                        Html::addCssClass($slinkOptions, 'active');
                        
                        $lines[] = '
                            <div class="pointer">
                                <div class="arrow"></div>
                                <div class="arrow_border"></div>
                            </div>
                        ';
                    }
                    
                    if (!isset($item['label']))
                        throw new InvalidConfigException("The 'label' option is required.");

                    $slabel = $item['label'];
                    $soptions = ArrayHelper::getValue($item, 'options', []);
                    
                    $scontent = Html::a($slabel, ArrayHelper::getValue($item, 'url', '#'), $slinkOptions);
                    $lines[] = Html::tag('li', $scontent, $soptions);
                }
                
                
                Html::addCssClass($ulOptions, 'submenu');

                $items = Html::tag('ul', implode("\n", $lines), $ulOptions);
			}
		}

		return Html::tag('li', $activeHtml . Html::a($label, $url, $linkOptions) . $items, $options);
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

			if (ltrim($route, '/') !== $this->route && strstr($this->route, ltrim($route, '/')) === false) { 
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
