<?php

namespace app\components;

use Yii;
use yii\grid\ActionColumn as YiiActionColumn;
use yii\helpers\Html;
use app\models\OcorrenciaStatus;


/**
 * @inheritdoc
 */
class OcorrenciaColumn extends YiiActionColumn
{
	/**
	 * @inheritdoc
	 */
	protected function initDefaultButtons()
	{
		$this->buttons['detalhes'] = function ($url, $model) {
			return Html::a('<i class="glyphicon glyphicon-search"></i>', $url, [
				'title' => Yii::t('yii', 'Ver detalhes'),
			]);
		};

		$this->buttons['aprovar'] = function ($url, $model) {

			if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
				return;
			}

			if($model->status != OcorrenciaStatus::AVALIACAO) {
				return;
			}

			return Html::a('<i class="glyphicon glyphicon-ok"></i>', $url, [
				'title' => Yii::t('yii', 'Aprovar'),
			]);
		};

		$this->buttons['reprovar'] = function ($url, $model) {

			if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
				return;
			}

			if($model->status != OcorrenciaStatus::AVALIACAO) {
				return;
			}

			return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
				'title' => Yii::t('yii', 'Reprovar'),
				'data-confirm' => Yii::t('yii', 'Você quer realmente reprovar esta ocorrência?'),
				'data-method' => 'post',
			]);
		};

		$this->buttons['mudar-status'] = function ($url, $model) {

			if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
				return;
			}

			if($model->status == OcorrenciaStatus::AVALIACAO) {
				return;
			}

			return Html::a('<i class="glyphicon glyphicon-transfer"></i>', $url, [
				'title' => Yii::t('yii', 'Mudar status'),
				'data-method' => 'post',
			]);
		};

		$this->buttons['tentativa-averiguacao'] = function ($url, $model) {

			if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
				return;
			}

			if($model->status == OcorrenciaStatus::AVALIACAO) {
				return;
			}

			return Html::a('<i class="glyphicon glyphicon-home"></i>', $url, [
				'title' => Yii::t('yii', 'Informar tentativa de averiguação'),
				'data-method' => 'post',
			]);
		};

		$this->buttons['anexo'] = function ($url, $model) {

			if(!$model->anexo) {
				return;
			}

			return Html::a('<i class="glyphicon glyphicon-paperclip"></i>', $url, [
				'title' => Yii::t('yii', 'Baixar anexo'),
			]);
		};

		$this->buttons['comprovante'] = function ($url, $model) {
			return Html::a('<i class="glyphicon glyphicon-download-alt"></i>', $url, [
				'title' => Yii::t('yii', 'Baixar comprovante de ocorrência'),
			]);
		};
	}
}
