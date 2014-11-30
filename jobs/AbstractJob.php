<?php
namespace app\jobs;

interface AbstractJob 
{
	public function run($params = []);
}