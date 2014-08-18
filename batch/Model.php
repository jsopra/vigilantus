<?php


namespace app\batch;

use yii\base\Model as YiiModel;
use Yii;

/**
 * Classe base para os modelos de carga de dados via arquivo.
 * As colunas da carga são definidas nos métodos abstratos a seguir, que utilizam
 * o atributo <code>$columns</code>.
 *
 * Parâmetros adicionais são processados como atributos normais do modelo.
 */
abstract class Model extends YiiModel
{
    /**
     * Armazena as colunas da carga
     * @var array
     */
    public $columns = [];

    /**
     * Caminho do arquivo da carga
     * @var string
     */
    public $file_path;

    /**
     * Linhas processadas
     * @var integer
     */
    public $processedRows = 0;

    /**
     * Linhas com erro
     * @var integer
     */
    public $failingRows = 0;

    /**
     * Caminho do arquivo CSV com os erros
     */
    public $errorsCsvPath;

    /**
     * Cabeçalhos das colunas do arquivo de carga.
     * @return array a chave é uma representa o ID do atributo, e o valor seu rótulo
     */
    abstract public function columnLabels();

    /**
     * Insere uma linha do arquivo no banco de dados.
     * @param Row $row O objeto que representa a linha do arquivo
     * @return boolean Se essa linha foi inserida com sucesso ou não
     */
    abstract public function insert($row);

    /**
     * Dicas dos campos na view das colunas do arquivo de carga.
     * @return array a chave é uma representa o ID do atributo, e o valor é o "hint" (dica)
     */
    public function columnHints()
    {
        return [];
    }

    /**
     * Retorna a posição da coluna para um determindo atributo.
     * Exemplo: se os dados para 'data_aniversario' estão na coluna 5 do CSV,
     * ele retorna 5.
     * @return integer|false Retorna a posição ou FALSE se não existir
     */
    public function getColumnPositionFor($attribute)
    {
        if (in_array($attribute, $this->columns)) {
            return array_search($attribute, $this->columns);
        }
        return false;
    }

    /**
     * Método chamado antes de começar a executar as inserções (método insert)
     * @return void
     */
    public function beforeInsertingAll()
    {

    }

    /**
     * Método chamado depois de executar todas as inserções (método insert)
     * @return void
     */
    public function afterInsertingAll()
    {

    }

    /**
     * Dicas dos campos na view dos parâmetros adicionais.
     * @return array a chave é uma representa o ID do atributo, e o valor é o "hint" (dica)
     */
    public function attributeHints()
    {
        return array();
    }

    /**
     * Retorna a dica ou explicação de um atributo
     * @return string $attribute
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();

        if (isset($hints[$attribute])) {
            return $hints[$attribute];
        }
    }

    /**
     * Retorna a dica ou explicação de uma coluna da carga
     * @return string $column
     */
    public function getColumnHint($column)
    {
        $hints = $this->columnHints();

        if (isset($hints[$column])) {
            return $hints[$column];
        }
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabel($attribute)
    {
        // Se o atributo se refere a uma coluna, busca o label correto
        if (false !== strpos($attribute, 'columns[')) {

            $columnsLabels = $this->columnLabels();

            $matches = array();
            preg_match('/columns\[(.*)\]/', $attribute, $matches);

            if ($matches) {

                $column = array_pop($matches);

                if (isset($columnsLabels[$column])) {
                    return $columnsLabels[$column];
                }
            }
        }

        return parent::getAttributeLabel($attribute);
    }

    /**
     * Cria e retorna um CSV de exemplo, contendo as colunas no cabeçalho
     * e uma linha vazia contendo somento os hints das colunas que tiverem.
     * @return string
     */
    public function getExampleFile()
    {
        $columns = $this->columnLabels();
        $hints = $this->columnHints();

        $delimiter = ';';

        $csv = implode($delimiter, $columns);
        $csv .= "\n";

        $emptyRow = array();

        foreach ($columns as $column => $label) {
            if (isset($hints[$column])) {
                $emptyRow[] = $hints[$column];
            } else {
                $emptyRow[] = '';
            }
        }

        $csv .= implode($delimiter, $emptyRow);
        $csv .= "\n";

        return $csv;
    }

    /**
     * @inheritdoc
     */
    public function safeAttributes()
    {
        $safeAttributes = parent::safeAttributes();

        if (!in_array('columns', $safeAttributes)) {
            $safeAttributes[] = 'columns';
        }

        return $safeAttributes;
    }

    /**
     * Insere cada linha da planilha no banco de dados.
     * Todos os dados serão inseridos em uma transaction.
     * Se a inserção falhar, somente a linha afetada será revertida
     */
    public function save($file)
    {
        $this->beforeInsertingAll();

        $errorsCsvPath = tempnam(sys_get_temp_dir(), 'erros-carga');
        rename($errorsCsvPath, $errorsCsvPath . '.csv');
        $errorsCsvPath .= '.csv';
        $this->errorsCsvPath = $errorsCsvPath;

        $errorsCsv = fopen($errorsCsvPath, 'w');

        $headerData = $file->getRow()->data;
        $headerData[] = 'Erros';
        fputcsv($errorsCsv, $headerData, $file->delimiter);

        $this->processedRows = 0;
        $this->failingRows = 0;

        $totalRows = $file->getRowsCount();

        while ($row = $file->getRow()) {

            $transaction = Yii::$app->db->beginTransaction();

            $row->model = $this;

            try {

                if ($this->insert($row)) {
                    $transaction->commit();
                } elseif ($row->hasErrors() == false) {
                    $row->addError('Erro desconhecido');
                }

            } catch (Exception $exception) {

                $message = $exception->getMessage();
                $trace = $exception->getTraceAsString();

                if (!YII_ENV_PROD) {
                    $message .= $trace;
                }

                $row->addError('Erro do sistema: ' . $message);
            }

            if ($row->hasErrors()) {

                $this->failingRows++;

                $rowData = $row->data;
                $errors = $row->getErrors();
                foreach ($errors as $key => $error) {
                    $errors[$key] = $error;
                }
                
                $rowData[] = implode(' - ', $errors);
                foreach ($rowData as $key => $value) {
                    $rowData[$key] = $value;
                }

                fputcsv($errorsCsv, $rowData, $file->delimiter);

                $transaction->rollBack();
            }

            $this->processedRows++;
        }

        fclose($errorsCsv);

        if ($this->failingRows == 0) {
            return true;
        }

        $this->afterInsertingAll();
    }
}
