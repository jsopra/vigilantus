<?php

namespace app\batch;

use yii\helpers\Html;
use yii\web\ViewAction;
use yii\web\UploadedFile;
use Yii;

/**
 * Action que permite fazer uma carga de dados através de uma planilha CSV
 */
class Action extends ViewAction
{
    /**
     * Classe do modelo que insere as linhas do arquivo CSV
     * @var string
     */
    public $modelClass;

    /**
     * Atributos adicionais para setar no objeto do modelo que insere linhas
     * <code>
     * 'modelAttributes' => array(
     *     'tipo_cliente' => isset($_GET['tipo_cliente']) ? $_GET['tipo_cliente'] : null,
     * ),
     * </code>
     * @var array
     */
    public $modelAttributes = array();

    /**
     * Classe do modelo que faz o upload do arquivo CSV
     * @var string Por padrão usará fidelize.carga.FCargaUpload
     */
    public $uploadClass;

    /**
     * Título da página nas views
     * @var string
     */
    public $pageTitle = 'Carga';

    /**
     * Caminho da view da ação de upload
     * @var string
     */
    public $uploadView = '@app/batch/views/upload';

    /**
     * Caminho da view da ação de selecionar as colunas
     * @var string
     */
    public $batchView = '@app/batch/views/batch';

    /**
     * Caminho de um View Partial com campos adicionais para o formulário de
     * seleção das posições das colunas. Pode ser usado para os parâmetros
     * adicionais, como "[x] Substituir registros existentes".
     * @var string
     */
    public $extraFieldsPartial;

    /**
     * Objeto que lê o arquivo
     * @var File
     */
    protected $_fileObject;

    /**
     * Action principal que chamará o form de upload ou o form de selecionar colunas.
     * @param integer $clear Se informado, cancelará o upload atual
     */
    public function run($clear = 0)
    {
        if ($clear) {
            $this->clearUploadedFile();
            return $this->controller->redirect(array($this->id));
        }

        if (!empty($_GET['downloadExample'])) {
            return $this->baixarArquivoExemplo();
        }

        return $this->hasUploadedFile() ? $this->telaCarga() : $this->telaUpload();
    }

    /**
     * Ação para quando faz o upload de um novo arquivo
     */
    protected function telaUpload()
    {
        $model = $this->getUploadObject();
        $class = explode('\\', get_class($model));
        $class = array_pop($class);

        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstanceByName($class . '[file]');

            if ($model->upload()) {
                Yii::$app->session->set($this->chaveUnica, $model->uploadedFile);
                return $this->controller->refresh();
            }
        }

        return $this->controller->render(
            $this->uploadView,
            array(
                'model' => $model,
                'pageTitle' => $this->pageTitle,
            )
        );
    }

    /**
     * Ação para quando vai processar o arquivo enviado
     */
    protected function telaCarga()
    {
        $model = $this->getBatchObject();
        $class = explode('\\', get_class($model));
        $class = array_pop($class);

        if (isset($_POST[$class])) {

            $model->attributes = $_POST[$class];

            if ($model->validate()) {

                $model->save($this->getFileObject());

                $message = $model->processedRows . ' linha(s) foram processadas. ';
                $type = 'success';

                if ($model->failingRows) {

                    $message .= $model->failingRows . ' possuem erros. ';
                    $type = 'error';

                    // @FIXME Tem um bug no assetManager, atualizar o Yii 2
                    // $url = Yii::$app->assetManager->publish($model->errorsCsvPath);

                    rename(
                        $model->errorsCsvPath,
                        Yii::getAlias(Yii::$app->assetManager->basePath) . DIRECTORY_SEPARATOR . basename($model->errorsCsvPath)
                    );

                    $url = Yii::getAlias(Yii::$app->assetManager->baseUrl) . '/' . basename($model->errorsCsvPath);

                    //////// Fim do bloco que pode ser removido ao corrigir

                    // @FIXME descomentar quando corrigir o Yii 2
                    // unlink($model->errorsCsvPath);

                    $message .= Html::a(
                        'Baixar planilha com as linhas que falharam e o motivo do erro',
                        $url
                    ) . '.';
                }

                Yii::$app->session->setFlash($type, $message);

                $this->clearUploadedFile();

                $url = [$this->id];

                foreach ($this->modelAttributes as $attribute => $value) {
                    $url[$attribute] = $value;
                }

                return $this->controller->redirect($url);
            }
        }

        return $this->controller->render(
            $this->batchView,
            [
                'model' => $model,
                'pageTitle' => $this->pageTitle,
                'exampleRow' => $this->getFileObject()->firstRow,
                'headerRow' => $this->getFileObject()->headerRow,
            ]
        );
    }

    /**
     * Faz o download de uma planilha de exemplo vazia
     */
    protected function baixarArquivoExemplo()
    {
        $exampleFile = $this->getBatchObject()->getExampleFile();

        header('Content-Description: File Transfer'); 
        header('Content-Type: text/csv;charset=' . Yii::$app->charset);
        header('Content-Disposition: attachment; filename="example.csv"');
        header('Content-Transfer-Encoding: binary'); 
        header('Expires: 0'); 
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
        header('Pragma: public'); 
        
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        die($exampleFile);
    }


    /**
     * @return boolean Se já foi feito o upload do arquivo ou não
     */
    protected function hasUploadedFile()
    {
        return Yii::$app->session->has($this->chaveUnica);
    }

    /**
     * Se houver um arquivo enviado, cancela o upload
     */
    protected function clearUploadedFile()
    {
        if ($this->hasUploadedFile()) {
            Yii::$app->session->set($this->chaveUnica, null);
        }
    }

    /**
     * Se houver um arquivo enviado, retorna o caminho dele
     * @return string
     */
    protected function getUploadedFileName()
    {
        if ($this->hasUploadedFile()) {
            return Yii::$app->session->get($this->chaveUnica);
        }
    }

    /**
     * Retorna uma instância do objeto que representa a carga
     * @return FCargaUpload
     */
    protected function getUploadObject()
    {
        $modelClass = $this->uploadClass;

        if (empty($modelClass)) {

            $modelClass = 'app\\batch\\Upload';
        }

        return new $modelClass;
    }

    /**
     * Retorna uma instância do objeto que representa a carga
     * @return FCargaModel
     */
    protected function getBatchObject()
    {
        if (empty($this->modelClass)) {
            throw new \Exception('Informe o atributo $modelClass de app\\batch\\Action.');
        }

        $modelClass = $this->modelClass;

        $object = new $modelClass;

        foreach ($this->modelAttributes as $attribute => $value) {
            $object->$attribute = $value;
        }

        if ($this->hasUploadedFile()) {
            $object->file_path = $this->getUploadedFileName();
        }

        return $object;
    }

    /**
     * Retorna uma instância do objeto que representa a carga
     * @return FCargaModel
     */
    protected function getFileObject()
    {
        if (null === $this->_fileObject) {

            $this->_fileObject = new File(
                $this->getUploadedFileName()
            );
        }

        return $this->_fileObject;
    }

    /**
     * Retorna uma chave única para identificar o processo de carga atual
     * @return string
     */
    protected function getChaveUnica()
    {
        $chave = array(
            Yii::$app->id,
            $this->controller->id,
            'carga',
            Yii::$app->user->id,
        );

        if ($this->controller->module)  {
            $chave[] = $this->controller->module->id;
        }

        return implode('.', $chave);
    }
}