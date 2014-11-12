<?php
namespace app\models\redis;

class Queue extends \yii\redis\ActiveRecord
{
    public function attributes()
    {
        return [
            'id', 
            'jobName',
            'params',
        ];
    }
    
   	public static function push($jobName, $params = [])
   	{
   		$job = new Queue;
   		$job->jobName = $jobName;
   		$job->params = serialize($params);
   		return $job->save();
   	}

   	public static function pop()
   	{
   		$model = Queue::find()->one();

   		if(!$model) {
   			return false;
   		}

   		$data = [
   			'jobName' => $model->jobName,
   			'params' => unserialize($model->params),
   		];

   		$model->delete();

   		return $data;
   	}
}