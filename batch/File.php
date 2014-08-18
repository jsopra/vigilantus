<?php
namespace app\batch;

use yii\base\Object;

class File extends Object
{
    /**
     * Separador de colunas do CSV
     * @var string
     */
    public $delimiter = ';';

    /**
     * @var resource Arquivo aberto
     */
    protected $_handle;

    /**
     * @var string Caminho do arquivo
     */
    protected $_filePath;

    /**
     * @var integer
     */
    protected $_currentRow = 0;

    /**
     * @var integer
     */
    protected $_rowsCount;

    /**
     * Construtor
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->_filePath = $filePath;
        $this->open();
    }

    /**
     * Abre o arquivo para leitura
     */
    protected function open()
    {
        $this->_handle = fopen($this->_filePath, 'r');
        $this->_currentRow = 0;
    }

    /**
     * Abre o arquivo para leitura
     */
    protected function close()
    {
        fclose($this->_handle);
        $this->_handle = null;
    }

    /**
     * Abre o arquivo para leitura
     */
    protected function isOpen()
    {
        return $this->_handle !== null;
    }

    /**
     * Reabre o arquivo para leitura e coloca o ponteiro no começo do arquivo
     */
    public function resetPosition()
    {
        if ($this->isOpen()) {
            $this->close();
            $this->open();
        } else {
            $this->open();
        }
    }

    /**
     * @return Row
     */
    public function getRow()
    {
        if (!$this->isOpen()) {
            $this->open();
        }

        $data = fgetcsv($this->_handle, 0, $this->delimiter);

        if (!$data) {
            return false;
        }

        $this->_currentRow++;

        foreach ($data as $key => $value) {

            $encoding = mb_detect_encoding($value, 'UTF-8, ISO-8859-1', true);

            if ($encoding != 'UTF-8') {
                $data[$key] = mb_convert_encoding($value, 'UTF-8', $encoding);
            }
        }

        $row = new Row;
        $row->number = $this->_currentRow;
        $row->data = $data;

        return $row;
    }

    /**
     * @return Row
     */
    public function getHeaderRow()
    {
        $this->resetPosition();

        return $this->getRow();
    }

    /**
     * @return Row
     */
    public function getFirstRow()
    {
        // Descarta o cabeçalho
        $this->getHeaderRow();

        return $this->getRow();
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->_filePath;
    }

    /**
     * @return integer
     */
    public function getRowsCount()
    {
        if ($this->_rowsCount === null) {

            $handle = fopen($this->_filePath, 'r');

            $this->_rowsCount = 0;

            // Descarta o header
            fgetcsv($handle, 0);

            while (fgetcsv($handle, 0)) {
                $this->_rowsCount++;
            }

            fclose($handle);
        }
        
        return $this->_rowsCount;
    }
}
