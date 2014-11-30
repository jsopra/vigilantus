<?php

namespace app\batch;

use yii\base\Model;
use Yii;

class Upload extends Model
{
    public $file;
    protected $uploaded_file;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Arquivo',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['file', 'required'],
            //['file', 'file', 'extensions' => 'csv'],
        ];
    }

    /**
     * Valida o arquivo e copia ele para o sistema
     * @return boolean Se o upload foi feito com sucesso
     */
    public function upload()
    {
        if ($this->validate()) {

            $tempFilePath = $this->file->tempName;

            // Corrige finais de linha
            $file = file_get_contents($tempFilePath);

            $file = str_replace("\r", "\n", $file);
            $file = str_replace("\n\n", "\n", $file);
            file_put_contents($tempFilePath, $file);

            $handle = fopen($tempFilePath, 'r');
            $header = trim(fgets($handle));
            $firstRow = trim(fgets($handle));
            fclose($handle);

            if (!$header || !$firstRow) {
                $this->addError('file', 'O arquivo deve conter pelo menos o cabeçalho e uma linha');
                return false;
            }

            $dirPath = Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'cargas';

            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777);
            }

            $newFilePath = $dirPath . DIRECTORY_SEPARATOR . md5_file($tempFilePath) . '.csv';

            if ($this->file->saveAs($newFilePath)) {

                $this->uploaded_file = $newFilePath;

                // Corrige encoding
                $encoding = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);

                if ($encoding != 'UTF-8') {
                    $file = mb_convert_encoding($file, 'UTF-8', $encoding);
                    unlink($newFilePath);
                    file_put_contents($newFilePath, $file);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @return string Caminho do arquivo após o upload
     */
    public function getUploadedFile()
    {
        return $this->uploaded_file;
    }
}