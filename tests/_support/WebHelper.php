<?php
namespace Codeception\Module;

use Codeception\Exception\ElementNotFound;

class WebHelper extends \Codeception\Module\WebDriver
{
    public function clickXpath($link, $context = null)
    {
        $page = $this->webDriver;
        if ($context) {
            $nodes = $this->match($this->webDriver, $context);
            if (empty($nodes)) {
                throw new ElementNotFound($context, 'CSS or XPath');
            }
            $page = reset($nodes);
        }
        $els = $this->match($page, $link);
        $el = reset($els);
        if (!$el) {
            throw new ElementNotFound($link, 'Link or Button or CSS or XPath');
        }
        $el->click();
    }
}
