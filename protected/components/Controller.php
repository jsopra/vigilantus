<?php
class Controller extends CController
{
	public $layout = 'application.views.layouts.main';
	
	public $contactForm;
	
	
	public function init() {
		parent::init();
		
		$this->contactForm = new ContactForm;
	}
}