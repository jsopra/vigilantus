<?php
/**
 * Behavior que formata data, hora e números no padrão do idioma configurado
 * 
 * Baseado no DateTimeI18NBehavior de Ricardo Grana.
 * 
 */
class I18NBehavior extends CActiveRecordBehavior
{

    public $dateOutcomeFormat     = 'Y-m-d';
    public $dateTimeOutcomeFormat = 'Y-m-d H:i:s';
    public $timeOutcomeFormat     = 'H:i:s';
    
    public $dateIncomeFormat      = 'yyyy-MM-dd';
    public $dateTimeIncomeFormat  = 'yyyy-MM-dd hh:mm:ss';
    public $timeIncomeFormat      = 'hh:mm:ss';
    
    /**
     * Todos os tipos de colunas que são internacionalizáveis
     * @var array 
     */
    protected $_i18nDbTypes = array(
        'date'     => array('date'),
        'datetime' => array('datetime', 'timestamp without time zone', 'timestamp with time zone', 'timestamp'),
        'time'     => array('time without time zone', 'time with time zone', 'time'),
        'float'    => array('decimal', 'numeric', 'real', 'double'),
    );
    
    /**
     * Antes de validar, converte floats para float
     * @param CModelEvent $event event parameter 
     */
    public function beforeValidate($event)
    {
        foreach ($this->getColumnsOfTypeForEvent($event, 'float') as $columnName) {            
            $event->sender->$columnName = self::parseFloat($event->sender->$columnName);
        }
        return true;
    }
    
    /**
     * Após validar, converte floats para string
     * @param CModelEvent $event event parameter 
     */
    public function afterValidate($event)
    {
        foreach ($this->getColumnsOfTypeForEvent($event, 'float') as $columnName) {

        	if (gettype($event->sender->$columnName) == 'double')
        		$event->sender->$columnName = Yii::app()->numberFormatter->formatDecimal(floatval($event->sender->$columnName));
        }
        return true;
    }
    
    /**
     * Antes de salvar, faz a conversão pro locale do banco de dados
     * @param CModelEvent $event event parameter
     * @return boolean 
     */
    public function beforeSave($event)
    {
        return $this->toSystem($event);
    }
    
    /**
     * Antes de salvar, faz a conversão pro locale do banco de dados
     * @param CModelEvent $event event parameter
     * @return boolean 
     */
    public function afterSave($event)
    {
        return $this->toI18n($event);
    }
    
    /**
     * Após o select, converte pro locale do Yii
     * @param CModelEvent $event event parameter
     * @return boolean 
     */
    public function afterFind($event)
    {
        return $this->toI18n($event);
    }
    
    /**
     * Converte número no formato do locale para float do PHP
     * @param string $value
     * @return float 
     */
    public static function parseFloat($value)
    {
        if (is_float($value) || $value === null) {
            return $value;
        }
        
        $agrupadorMilhares = Yii::app()->locale->getNumberSymbol('group');
        $agrupadorDecimal  = Yii::app()->locale->getNumberSymbol('decimal');

        $emptyValue = str_replace(array($agrupadorMilhares, $agrupadorDecimal), '', (string) $value);
        $emptyValue = preg_replace('/[0-9]/', '', $emptyValue);

        // Se contém algum caractere que não sejam os separadores ou números, retorna a própria string pra estourar na validação
        if (strlen($emptyValue) != 0) {
            return $value;
        }

        $numero = str_replace($agrupadorMilhares, '', (string) $value);
        
        return (float) str_replace($agrupadorDecimal, '.', (string) $numero);
    }
    
    /**
     * Retorna colunas de um determinado tipo
     * @param CModelEvent $event event parameter
     * @param string $type Deve estar no atributo _i18nDbTypes
     * @return array 
     */
    protected function getColumnsOfTypeForEvent($event, $type)
    {
        $columns = array();
        
        foreach ($event->sender->tableSchema->columns as $columnName => $column) {
            
            $dbType = preg_replace('/[^a-z\s]/', '', strtolower($column->dbType));
            
            if ($event->sender->$columnName instanceof CDbExpression) {
                continue;
            }
            
            if (gettype($event->sender->$columnName) == 'string' && !strlen($event->sender->$columnName)) {
                $event->sender->$columnName = null;
                continue;
            }

            if (null === $event->sender->$columnName) {
                continue;
            }
            
            if (in_array($dbType, $this->_i18nDbTypes[$type])) {
                $columns[] = $columnName;
            }
        }
        
        return $columns;
    }
    
    /**
     * Converte valores nativos (floats do PHP, datas do banco, etc.) para formato internacionalizado
     * @param CModelEvent $event event parameter
     * @return boolean 
     */
    protected function toI18n($event)
    {
        foreach ($this->getColumnsOfTypeForEvent($event, 'date') as $columnName) {
            
            $event->sender->$columnName = FidelizeDate::getDate(
                CDateTimeParser::parse(
                    substr($event->sender->$columnName, 0, 10),
                    $this->dateIncomeFormat
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'time') as $columnName) {
            
            $event->sender->$columnName = FidelizeDate::getTime(
                CDateTimeParser::parse(
                    substr($event->sender->$columnName, 0, 8),
                    $this->timeIncomeFormat
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'datetime') as $columnName) {
            
           $event->sender->$columnName = FidelizeDate::getDateTime(
                CDateTimeParser::parse(
                    substr($event->sender->$columnName, 0, 19),
                    $this->dateTimeIncomeFormat
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'float') as $columnName) {
            
           $event->sender->$columnName = Yii::app()->numberFormatter->formatDecimal(floatval($event->sender->$columnName));
        }
        
        return true;
    }
    
    /**
     * Converte valor internacionalizado para valores nativos do PHP
     * @param CModelEvent $event event parameter
     * @return type 
     */
    protected function toSystem($event)
    {
        foreach ($this->getColumnsOfTypeForEvent($event, 'date') as $columnName) {
            
            $event->sender->$columnName = date(
                $this->dateOutcomeFormat,
                CDateTimeParser::parse(
                    $event->sender->$columnName,
                    FidelizeDate::getDateFormat()
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'time') as $columnName) {
            
            $valor = $event->sender->$columnName;
                
            // Se só preencheu hora:minutos, inclui segundos
            if (strlen($valor) == 5) {
                $valor .= ':00';
            }

            $event->sender->$columnName = date(
                $this->timeOutcomeFormat,
                CDateTimeParser::parse(
                    $valor,
                    FidelizeDate::getTimeFormat()
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'datetime') as $columnName) {
            
            $valor = $event->sender->$columnName;
                
            // Se só preencheu dia-mes-ano hora:minutos, inclui segundos
            if (strlen($valor) == 16) {
                $valor .= ':00';
            }
            // Se não colocou hora
            elseif (strlen($valor) == 10) {
                $valor .= ' 00:00:00';
            }
            
            $event->sender->$columnName = date(
                $this->dateTimeOutcomeFormat, CDateTimeParser::parse(
                    $event->sender->$columnName,
                    FidelizeDate::getDateTimeFormat()
                )
            );
        }
        
        foreach ($this->getColumnsOfTypeForEvent($event, 'float') as $columnName) {
            
            $event->sender->$columnName = self::parseFloat($event->sender->$columnName);
        }

        return true;
    }
}
