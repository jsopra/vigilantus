<?php

namespace app\batch;

use yii\base\Object;

class Row extends Object
{
    /**
     * @var integer Número da linha no arquivo
     */
    public $number;

    /**
     * @var array Dados da linha
     */
    public $data;

    /**
     * @var FCargaModel Modelo que está utilizando esta linha
     */
    public $model;

    /**
     * @var array Erros que ocorreram nesta linha
     */
    protected $_errors = array();

    /**
     * Retorna o valor de um atributo desta linha
     * @param string $attribute O nome do atributo
     * @return Mixed
     */
    public function getValue($attribute)
    {
        if (false !== ($position = $this->model->getColumnPositionFor($attribute))) {

            if (isset($this->data[$position])) {
                return $this->data[$position];
            }
        }
    }

    /**
     * Adiciona um novo erro
     * @param string $message
     */
    public function addError($message)
    {
        $this->_errors[] = $message;
    }

    /**
     * Adiciona erros a partir de um objeto
     * @param CModel $object Objeto contendo os erros
     */
    public function addErrorsFromObject($object)
    {
        foreach ($object->errors as $attribute => $errors) {

            $this->addError(
                $object->getAttributeLabel($attribute) . ': ' . implode(' - ', $errors)
            );
        }
    }

    /**
     * @return array Array com as mensagens de erro
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return boolean Se tem erros ou não
     */
    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }
}