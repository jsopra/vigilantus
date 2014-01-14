<?php

namespace tests\_pages;

use yii\codeception\BasePage;

class ContatoPage extends BasePage
{
	public $route = 'site/contato';

	/**
	 * @param array $contactData
	 */
	public function submit(array $contactData)
	{
		$data = [];
		foreach ($contactData as $name => $value) {
			$data["ContatoForm[$name]"] = $value;
		}
		$this->guy->submitForm('#contact-form', $data);
	}
}
