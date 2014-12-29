<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\Cliente;

class ClienteHelper extends YiiStringHelper 
{

	public static function getDadosContato($model)
	{
		$html = '<ul>';

		$html .= '<li><strong>Nome:</strong> ' . $model->nome_contato . '</li>';
		$html .= '<li><strong>Email:</strong> ' . $model->email_contato . '</li>';
		$html .= '<li><strong>Telefone:</strong> ' . $model->telefone_contato . '</li>';
		$html .= '<li><strong>Departamento:</strong> ' . $model->departamento . '</li>';
		$html .= '<li><strong>Cargo:</strong> ' . $model->cargo . '</li>';

        $html .= '</ul>';

		return $html;
	}
}