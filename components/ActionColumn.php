<?php

namespace app\components;

use Yii;
use yii\grid\ActionColumn as YiiActionColumn;
use yii\helpers\Html;

/**
 * @inheritdoc
 */
class ActionColumn extends YiiActionColumn
{
	/**
	 * @inheritdoc
	 */
	protected function initDefaultButtons()
	{
		if (!isset($this->buttons['view'])) {
			$this->buttons['view'] = function ($url, $model) {
				return Html::a('<i class="table-view"></i>', $url, [
					'title' => Yii::t('yii', 'View'),
				]);
			};
		}
		if (!isset($this->buttons['update'])) {
			$this->buttons['update'] = function ($url, $model) {
				return Html::a('<i class="table-edit"></i>', $url, [
					'title' => Yii::t('yii', 'Update'),
				]);
			};
		}
		if (!isset($this->buttons['delete'])) {
			$this->buttons['delete'] = function ($url, $model) {
				return Html::a('<i class="table-delete"></i>', $url, [
					'title' => Yii::t('yii', 'Delete'),
					'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
					'data-method' => 'post',
				]);
			};
		}
	}
}
