<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\redis\Queue;
use app\jobs;

class QueueController extends Controller
{

    public function actionIndex()
    {

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionRun()
    { 
        $job = Queue::pop();

        if(!$job)  {
            echo 'Nenhum job';
            return Controller::EXIT_CODE_NORMAL;
        }

        $jobName = '\\app\\jobs\\' . $job['jobName'];

        echo 'Executando job:' . $jobName;

        $object = new $jobName;
        $object->run($job['params']); 

        echo 'Executado!';  

        return Controller::EXIT_CODE_NORMAL;
    }
}