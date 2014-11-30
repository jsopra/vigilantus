<?php

namespace tests\_pages;

use yii\codeception\BasePage;

class IndexPage extends BasePage
{
	public $route = '/';

	/**
	 * @param array $contactData
	 */
	public function submitFeedback(array $feedbackData)
	{
		$data = [];
		foreach ($feedbackData as $name => $value) {
			$data["FeedbackForm[$name]"] = $value;
		}
		$this->guy->preenchoFormulario('#feedback-form', $data);
		$this->guy->clico('Enviar', '#feedback-form');
	}
}
