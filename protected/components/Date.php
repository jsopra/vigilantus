<?php
/**
 * Classe que simplifica as chamadas ao Yii::app()->locale->dateFormatter
 * e centraliza dados de qual padrão de data/hora de cada idioma será usado: * 
 * (short, medium, long, full)
 */
class Date extends CComponent
{
    /**
     * @var string Formato de data utilizado no projeto
     */
    protected static $dateFormatLength = 'medium';
    
    /**
     * @var string Formato de hora utilizado no projeto
     */
    protected static $timeFormatLength = 'medium';
    
    /**
     * @return string O formato de data do projeto no idioma atual do Yii
     */
    public static function getDateFormat()
    {
        return Yii::app()->locale->getDateFormat(self::$dateFormatLength);
    }
    
    /**
     * @return string O formato de data/hora do projeto no idioma atual do Yii
     */
    public static function getDateTimeFormat()
    {
        return strtr(
            Yii::app()->locale->dateTimeFormat, array(
            "{0}" => Yii::app()->locale->getTimeFormat(self::$timeFormatLength),
            "{1}" => Yii::app()->locale->getDateFormat(self::$dateFormatLength)
            )
        );
    }
    
    /**
     * @return string O formato de hora do projeto no idioma atual do Yii
     */
    public static function getTimeFormat()
    {
        return Yii::app()->locale->getTimeFormat(self::$timeFormatLength);
    }
    
		/**
		 * Recebe uma string de data e o formato dessa string de data e retorna
		 * uma data parseada no formato i18N do usuário atual.
		 * 
		 * Usando: 
		 * 
		 * LLDate::getDateFromString("2005-05-04", 'yyyy-MM-dd');
		 * ou
		 * LLDate::getDateFromString("20/05/11", 'dd/MM/yy');
		 * ou
		 * LLDate::getDateFromString("20/05/11"); // caso sua language seja pt_br por exemplo
		 * 
		 * @param type $dateString Uma string de Data
		 * @param type $dateStringformat um formato
		 * @return type 
		 */
		public static function getDateFromString($dateString, $dateStringformat = null){
			$format = ($dateStringformat == null ) ? self::getDateFormat() : $dateStringformat;
			$timestamp = CDateTimeParser::parse($dateString, $format);
			return self::getDate($timestamp);
		}
		
		/**
		 * Recebe uma string de data e o formato dessa string de data e retorna
		 * uma data parseada no formato i18N do usuário atual.
		 * 
		 * Usando: 
		 * 
		 * LLDate::getDateDbFormat("2005-05-04", 'yyyy-MM-dd');
		 * ou
		 * LLDate::getDateDbFormat("20/05/11", 'dd/MM/yy');
		 * ou
		 * LLDate::getDateDbFormat("20/05/11"); // caso sua language seja pt_br por exemplo
		 * 
		 * @param type $dateString Uma string de Data
		 * @param type $dateStringformat um formato
		 * @return type 
		 */
		public static function getDateDbFormat($dateString, $dateStringformat = null){
			$format = ($dateStringformat == null ) ? self::getDateFormat() : $dateStringformat;
			$timestamp = CDateTimeParser::parse($dateString, $format);
			return date('Y-m-d', $timestamp);
		}
		 
		
    /**
		 * Recebe uma data em Timestamp e retorna uma string com a data formatada para
		 * a laguage do usuário
		 * 
     * @param integer $timestamp Timestamp (se for nulo, pega a hora atual)
     * @return string A data no formato do idioma atual do Yii
     */
    public static function getDate($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = time();
        }
        
        return Yii::app()->locale->getDateFormatter()->formatDateTime($timestamp, self::$dateFormatLength, null);
    }
    
		
		/**
		 * Recebe uma string de data/hora e o formato dessa string de data e retorna
		 * uma data parseada no formato i18N do usuário atual.
		 * 
		 * Usando: 
		 * 
		 * LLDate::getDateFromString("2005-05-04", 'yyyy-MM-dd');
		 * ou
		 * LLDate::getDateFromString("20/05/11", 'dd/MM/yy');
		 * 
		 * @param type $dateTimeString Uma string de Data
		 * @param type $dateStringformat um formato
		 * @return type 
		 */
		public static function getDateTimeFromString($dateTimeString, $dateTimeStringformat){
			$timestamp = CDateTimeParser::parse($dateTimeString, $dateTimeStringformat);
			return self::getDateTime($timestamp);
		}
		
		
    /**
     * @param integer $timestamp Timestamp (se for nulo, pega a hora atual)
     * @return string A data/hora no formato do idioma atual do Yii
     */
    public static function getDateTime($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = time();
        }
        
        return Yii::app()->locale->getDateFormatter()->formatDateTime($timestamp, self::$dateFormatLength, self::$timeFormatLength);
    }
    
    /**
     * @param integer $timestamp Timestamp (se for nulo, pega a hora atual)
     * @return string A hora no formato do idioma atual do Yii
     */
    public static function getTime($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = time();
        }
        
        return Yii::app()->locale->getDateFormatter()->formatDateTime($timestamp, null, self::$timeFormatLength);
    }

    public static function extractDataFromDateWithTimezone($dateTimeString)
	{
		list($data, $hora) = explode(' ', $dateTimeString);
		return self::getDateFromString($data, 'yyyy-MM-dd');
	}
}
