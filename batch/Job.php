<?php
/**
 * Job que faz a carga das informações em segundo plano
 */
class FCargaJob
{
    /**
     * Armazena os erros de cada linha
     * @var array
     * <code>
     * array(
     *     0 => array('name' => array('Nome não pode ficar em branco')),
     *     5 => array(
     *         'name' => array('Nome não pode ficar em branco'),
     *         'age' => array('Idade não pode ser inferior a 18'),
     *     ),
     * )
     * </code>
     */
    protected $rowsErrors = array();

    /**
     * Total de linhas processadas (com ou sem erros)
     * @var integer
     */
    protected $processedRows = 0;

    /**
     * Total de linhas com erros
     * @var integer
     */
    protected $failingRows = 0;

    /**
     * Caminho para o arquivo temporário contendo as linhas que deram erro na carga
     * @var string
     */
    protected $errorsArchivedCsvPath;

    /**
     * Objeto deste job
     * @var FidelizeBackgroundJob
     */
    protected $job;

    public function processar($atributos)
    {
        Yii::import('fidelize.carga.File');

        $category = 'FCargaJob';
        
        $this->job = FidelizeBackgroundJob::model()->findByPk(
            $atributos['FidelizeBackgroundJob_id']
        );

        $model = new $atributos['model'];
        $file = new File($atributos['file_path']);

        foreach ($atributos['attributes'] as $attribute => $value) {
            $model->$attribute = $value;
        }

        Yii::log(
            'Iniciando carga "' . get_class($model) . '" com o arquivo "' . $file->filePath . '"',
            CLogger::LEVEL_INFO,
            $category
        );

        $this->save($model, $file);

        $this->enviarEmail(
            $model->email_destinatary,
            $atributos['page_title']
        );

        Yii::log(
            'Carga concluída com ' . ($this->failingRows ? 'erros' : 'sucesso'),
            CLogger::LEVEL_INFO,
            $category
        );

        return true;
    }

    /**
     * Insere cada linha da planilha no banco de dados.
     * Todos os dados serão inseridos em uma transaction.
     * Se a inserção falhar, somente a linha afetada será revertida
     */
    public function save($model, $file)
    {
        $model->beforeInsertingAll();

        $errorsCsvPath = tempnam(sys_get_temp_dir(), 'erros-carga');
        rename($errorsCsvPath, $errorsCsvPath . '.csv');
        $errorsCsvPath .= '.csv';

        $errorsCsv = fopen($errorsCsvPath, 'w');

        $headerData = $file->getRow()->data;
        $headerData[] = 'Erros';
        fputcsv($errorsCsv, $headerData, $file->delimiter);

        $this->processedRows = 0;
        $this->failingRows = 0;
        $this->rowsErrors = array();

        $totalRows = $file->getRowsCount();

        while ($row = $file->getRow()) {

            $transaction = Yii::$app->db->beginTransaction();

            $row->model = $model;

            try {

                if ($model->insert($row)) {
                    $transaction->commit();
                } elseif ($row->hasErrors() == false) {
                    $row->addError('Erro desconhecido');
                }

            } catch (Exception $exception) {

                $message = $exception->getMessage();
                $trace = $exception->getTraceAsString();

                if (defined('_ENVIRONMENT_') && in_array(_ENVIRONMENT_, array('desenvolvimento', 'development'))) {
                    $message = $trace;
                }

                $row->addError('Erro do sistema: ' . $message);
                Yii::log(
                    "Erro do sistema:\n" . implode(';', $row->data) . "\n" . $trace,
                    CLogger::LEVEL_ERROR,
                    'FCargaJob'
                );
            }

            if ($row->hasErrors()) {

                $this->failingRows++;
                $this->rowsErrors[$row->number] = $row->getErrors();

                $rowData = $row->data;
                $errors = $row->getErrors();
                foreach ($errors as $key => $error) {
                    $encoding = mb_detect_encoding($error, 'UTF-8, ISO-8859-1', true);
                    if ($encoding != 'ISO-8859-1') {
                        $errors[$key] = mb_convert_encoding($error, 'ISO-8859-1', $encoding);
                    }
                }
                $rowData[] = implode(' - ', $errors);

                foreach ($rowData as $key => $value) {

                    $encoding = mb_detect_encoding($value, 'UTF-8, ISO-8859-1', true);
                    if ($encoding != 'ISO-8859-1') {
                        $rowData[$key] = mb_convert_encoding($value, 'ISO-8859-1', $encoding);
                    }
                }

                fputcsv($errorsCsv, $rowData, $file->delimiter);

                $transaction->rollBack();
            }

            $this->processedRows++;

            $this->job->progress = ($this->processedRows / $totalRows) * 100;
            $this->job->update('progress');
        }

        fclose($errorsCsv);

        if ($this->failingRows == 0) {
            return true;
        } else {
            $this->errorsArchivedCsvPath = $errorsCsvPath;
        }

        $model->afterInsertingAll();
    }
    
    /**
     * Dispara um e-mail
     * @param string $destinatario
     * @param string $mensagem 
     * @return boolean Se enviou com sucesso
     */
    protected function enviarEmail($destinatario, $assunto = 'Carga concluída')
    {
        $body = '';
        $email = new YiiMailMessage;

        if (defined('_NOME_FABRICANTE_')) {
            $assunto = _NOME_FABRICANTE_ . ' - ' . $assunto;
        }

        if (defined('_EMAIL_FROM_')) {
            $email->from = _EMAIL_FROM_;
        }

        if ($this->failingRows) {
            $email->attach(Swift_Attachment::fromPath($this->errorsArchivedCsvPath));
            $body = '<p>A carga dos dados do arquivo foi concluída com erros. ' . $this->processedRows . ' linha(s) foram processadas, sendo ' . $this->failingRows . ' com erro.</p>';
            $body .= '<p>Corrija os erros na planilha em anexo e faça o upload novamente.</p>';
        } else {
            $body = '<p>A carga dos dados do arquivo foi concluída com sucesso.</p>';
            $body .= '<p>' . $this->processedRows . ' linha(s) foram processadas.</p>';
        }

        $email->setSubject($assunto);
        $email->setTo($destinatario);
        $email->setBody($body, 'text/html');

        return Yii::$app->mail->send($email);
    }
}