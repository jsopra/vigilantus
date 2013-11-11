<?php
/**
 * Widget gerador de calendários
 * 
 * Ele gera calendários somente para um mês, para intervalos de dias, ou de meses.
 * 
 * Para gerar um calendário de março de 2011:
 * 
 * <pre>
 * $this->widget('application.extensions.CCalendarGenerator', array(
 *     'data'               => '03/2011',
 *     'javaScriptFunction' => 'carregarData', // chama carregarData(dia, mes, ano) ao clicar em um dia
 *     'calendarNavigation' => true, // Navegação pelos meses
 * ));
 * </pre>
 * 
 * Para gerar um calendário completo de 1989:
 * 
 * <pre>
 * $this->widget('application.extensions.CCalendarGenerator', array(
 *     'data' => '1989' 
 * ));
 * </pre>
 * 
 * Para gerar um calendário somente dos dias 15/05/2011 a 30/05/2011
 * 
 * <pre>
 * $this->widget('application.extensions.CCalendarGenerator', array(
 *     'dataInicial' => '15/05/2011',
 *     'dataFinal'   => '30/05/2011',
 * ));
 * </pre>
 * 
 * Para mostrar eventos no calendário (a classe CSS padrão é "event"):
 * 
 * <pre>
 * $this->widget('application.extensions.CCalendarGenerator', array(
 *     'data' => '1989',
 *     'eventos' => array(
 *         '09/11/1989' => 'Queda do muro de berlim', // Sem parâmetros
 *         '22/12/1989' => array(
 *             'title' => 'Revolução Romena',
 *             'click' => 'nomeFuncao',
 *             'class' => 'dia-guerra',
 *             'link'  => 'http://pt.wikipedia.org/wiki/Rom%C3%AAnia',
 *         ),
 *     ),
 * ));
 * </pre>
 * 
 */
class CCalendarGenerator extends CWidget
{
    /**
     * Gera calendário que inicia e termina na mesma data.
     * 
     * Se "$data" for definido, sobrescreve os outros atributos.
     * 
     * Pode estar nos formatos:
     * 
     * dd/mm/aaaa
     * mm/aaaa
     * aaaa
     * 
     * @var string 
     */
    public $data;
    public $dataInicial;
    public $dataFinal;
    
    /**
     * Função javascript que chamará ao clicar sobre uma data
     * @var string 
     */
    public $javaScriptFunction;
    
    /**
     * Indica se haverá ou não navegação pelo calendário
     * @var boolean 
     */
    public $calendarNavigation = false;
    
    /**
     * Eventos que serão destacados no calendário
     * @var array
     */
    public $eventos;
    
    /**
     * TODO Inicializa as variáveis
     */
    public function init()
    {
        // Se não houver data inicial ou final, e o outro sim
		if ($this->dataInicial && !$this->dataFinal) {
            throw new Exception('Foi informada uma data inicial, mas não final');
        }
        else if (!$this->dataInicial && $this->dataFinal) {
            throw new Exception('Foi informada uma data final, mas não inicial');
        }
        else if (!$this->dataInicial && !$this->dataFinal && !$this->data) {
            throw new Exception('Não foi informada nenhuma data');
        }
        
        // Prepara as datas
        if ($this->data) {
            $this->dataInicial = $this->_getDataCompleta($this->data);
            $this->dataFinal   = $this->_getDataCompleta($this->data, true);
        }
        else {
            
            $this->dataInicial = $this->_getDataCompleta($this->dataInicial);
            $this->dataFinal   = $this->_getDataCompleta($this->dataFinal, true);
            
            // Obtém o início e fim na ordem correta
            $datas = array(
                implode('.', array_reverse(explode('/', $this->dataInicial))),
                implode('.', array_reverse(explode('/', $this->dataFinal)))
            );	

            sort($datas);
            
            $this->dataInicial = implode('/', array_reverse(explode('.', array_shift($datas))));
            $this->dataFinal   = implode('/', array_reverse(explode('.', array_shift($datas))));
        }        
    }
    
    /**
     * Monta e renderiza o calendário
     */
	
    public function run()
    {
        $xhtml = '<div class="calendar">';
        
        $dataInicio = explode('/', $this->dataInicial);
        $dataFim    = explode('/', $this->dataFinal);
        
        $timeInicio = mktime(0, 0, 0, $dataInicio[1], $dataInicio[0], $dataInicio[2]);
        $timeFinal  = mktime(0, 0, 0, $dataFim[1], $dataFim[0], $dataFim[2]);
		
		$de   = intval(date('Y', $timeInicio));
		$para = intval(date('Y', $timeFinal));
        
        for ($ano = $de; $ano <= $para; $ano++) {
			
			$xhtml .= '<div class="year-' . $ano . '">';
			
			// Se for o primeiro ou último ano
			$mesInicial = 1;
			$mesFinal   = 12;
			
			// Se for o primeiro ou último ano do calendário
			if ($ano == $de || $ano == $para) {
				if ($ano == $de) {
					$mesInicial = intval(date('m', $timeInicio));					
				}
				if ($ano == $para) {
					$mesFinal = intval(date('m', $timeFinal));	
				}
			}
			
			// Renderiza os meses
			for ($mes = $mesInicial; $mes <= $mesFinal; $mes++) {
                
                // Se for o primeiro ou último mês de todo o calendário
                $primeiroDia = 1;
                $ultimoDia   = 31;
                
                $showPrevLink = $showNextLink = false;
                
                if (($ano == $de && $mes == $mesInicial) || ($ano == $para && $mes == $mesFinal)) {
                    if ($ano == $de && $mes == $mesInicial) {
                        $primeiroDia = intval(date('d', $timeInicio));
                        if ($this->calendarNavigation) $showPrevLink = true;
                    }
                    if ($ano == $para && $mes == $mesFinal) {
                        $ultimoDia = intval(date('d', $timeFinal));	
                        if ($this->calendarNavigation) $showNextLink = true;
                    }
                }
				
				$xhtml .= '<div class="calendar-month month-' . $mes . '">'
				        . $this->_htmlMes($mes, $ano, $primeiroDia, $ultimoDia, $showPrevLink, $showNextLink)
						. CHtml::link(Yii::t('Site', 'novo evento'), 'javascript:exibir_form_novo("");',array('id'=>'','class'=>'btn_agenda cinza'))
                        . '</div>'
                ;
				
			}
			
			$xhtml .= '</div>';
		}
		
        $xhtml .= '</div>';
		
		echo $xhtml;
    }
    
    /**
     * Renderiza o HTML de um mês de um ano
     * @param int     $mes
     * @param int     $ano
     * @param int     $diaInicial
     * @param int     $diaFinal
     * @param boolean $showPrevLink Indica se mostra botão para carregar meses anteriores
     * @param boolean $showNextLink Indica se mostra botão para carregar meses posteriores
     * 
     * @return string 
     */
    protected function _htmlMes($mes, $ano, $diaInicial, $diaFinal, $showPrevLink, $showNextLink)
    {
        $xhtml = '';
        
        $nomesMeses = Yii::app()->getLocale()->getMonthNames();
		
		// Calcula o número de dias neste mês
		$numberOfDays = date('t', mktime(0, 0, 0, $mes, 1, $ano));
		
		// Obtém nome do mês
		$monthName = $nomesMeses[intval($mes)];
		
		// Calcula o dia da semana do primeiro dia do mês
		$j = date('w', mktime(0, 0, 0, $mes, 1, $ano));
		
		// Ajusta os dias do começo do calendário
		$adjustment = '';
		
		for ($k = 1; $k <= $j; $k++) { 
			$adjustment .= '<td class="another-month">&nbsp;</td>';
		}
        
        // Mês e comandos
        $linhaMes = '';
        $celulaMes = '<a href="javascript:void(0)" onclick="mostrarMes(\''
                   . $mes . '\')">' . $monthName . ' / ' . $ano . '</a>'
        ;
        if ($showNextLink && $showPrevLink) {
            $linhaMes = '<td class="load-prev-month" onclick="loadPrevCalendar('
                      . $mes . ',' . $ano
                      . ')"></td><td colspan="5" class="month-name">' . $celulaMes
                      . '</td><td class="load-next-month" onclick="loadNextCalendar('
                      . $mes . ',' . $ano
                      . ')"></td>'
            ;
        }
        else if ($showNextLink) {
            $linhaMes = '<td colspan="6" class="month-name">' . $celulaMes
                      . '</td><td class="load-next-month" onclick="loadNextCalendar('
                      . $mes . ',' . $ano . ')"></td>'
            ;
        }
        else if ($showPrevLink) {
            $linhaMes = '<td class="load-prev-month" onclick="loadPrevCalendar('
                      . $mes . ',' . $ano . ')"></td><td colspan="6" class="month-name">'
                      . $celulaMes . '</td>'
            ;
        }
        else {
            $linhaMes = '<td colspan="7" class="month-name">' . $celulaMes . '</td>';
        }
		
		// Cabeçalho do calendário
		$xhtml .= '<table><thead><tr class="mes_calendario">' . $linhaMes
		        . '</tr><tr>'
		        . '<th>D</th>'
		        . '<th>S</th>'
		        . '<th>T</th>'
		        . '<th>Q</th>'
		        . '<th>Q</th>'
		        . '<th>S</th>'
		        . '<th>S</th>'
		        . '</tr></thead><tbody><tr>' . $adjustment
        ;
		
		// Dias do mês
		for ($i = 1; $i <= $numberOfDays; $i++) {
			
			// Verifica se existe um link ou estilo
			$link      = '';
			$linkEnd   = '';
			$class     = 'day-' . $i;
			$title     = '';
			$tdOnclick = '';
            $classEvento = '';
            
            $data = str_pad($i, 2, '0', STR_PAD_LEFT) . '/'
                  . str_pad($mes, 2, '0', STR_PAD_LEFT) . '/'
                  . str_pad($ano, 4, '0', STR_PAD_LEFT)
            ;
			
            // Se a data for a atual
            if (date('d/m/Y') == $data) {
                $class .= ' today';
            }
            
            // Internacionaliza a data
            $data = Yii::app()->dateFormatter->format(
                FidelizeDate::getDateFormat(),
	            CDateTimeParser::parse($data, 'dd/MM/yyyy')
            );
            
			if (isset($this->eventos[$data])) {
                
                $classEvento = ' event';
                
                // Se for só uma descrição, vira um title
                if (!is_array($this->eventos[$data])) {
                    $this->eventos[$data] = array('title' => $this->eventos[$data]);
                }
				
				// Verifica o estilo
				if (isset($this->eventos[$data]['class'])) {
					$class .= ' ' . $this->eventos[$data]['class'];
				}
				
				$onclick = '';
				
				// Verifica o onclik
				if (isset($this->eventos[$data]['click'])) {
					$onclick = ' onclick="' . $this->eventos[$data]['click'] . '"';
				} 
				
				// Verifica o link
				if (isset($this->eventos[$data]['link'])) {
					$link = '<a href="' . $this->eventos[$data]['link'] . '"'
					      . $onclick . '>';
					$linkEnd = '</a>';
				}
				
				// Verifica o título
				if (isset($this->eventos[$data]['title'])) {
					$title = ' title="' . $this->eventos[$data]['title'] . '"';
				}
			}
            
            // Se houver evento no onclick
			if (isset($this->javaScriptFunction) &&
                (!isset($this->eventos[$data]) || !isset($this->eventos[$data]['click']))) {
				$tdOnclick = ' onclick="' . $this->javaScriptFunction . '(' . $i . ',' . $mes . ',' . $ano . ')"';
			}
			
			// Imprime a célula
			if ($i >= $diaInicial && $i <= $diaFinal) {
				$xhtml .=  '<td class="' . $class . $classEvento . '" ' . $title . $tdOnclick . '>' . $link . $i . $linkEnd . '</td>';
			}
			else {
				$xhtml .=  '<td class="out-of-range day-' . $i . '">&nbsp</td>';
			}
			
			// Elimina o ajuste de dias no começo do mês
		    $adjustment = '';
		    
		    // Dia da semana
			$j++;
			
			// Ajuste à direita, se necessário
			if ($i == $numberOfDays) {
				
				if ($j != 7) {
					
					for ($k = 0; $k < (7 - $j); $k++) {
						$xhtml .= '<td class="another-month">&nbsp;</td>';
					}
				}
				
				$xhtml .= '</tr>';
			}
			
			// Senão apenas verifica se é o último dia da semana
			else if ($j == 7) {
				
				$xhtml .=  '</tr><tr>';
				$j = 0;
				
			}
		
		}
		
		$xhtml .=  '</tbody></table>';
        	
		return $xhtml;
    }
    
    /**
     * Pega uma parte de uma data (03/2009) e transfornuma numa completa (01/03/2009).
     * 
     * @param string  $data      A data ou parte de data
     * @param boolean $dataFinal Indica se a data será final
     * @return string 
     */
    protected function _getDataCompleta($data, $dataFinal = false)
    {
        $dia = $mes = $ano = 0;
        
        // Monta datas completas
        $data = explode('/', $data);
        
        if (count($data) > 1) {
            
            $ano = array_pop($data);
            $mes = array_pop($data);
            
            if (count($data)) $dia = array_pop($data);
        }
        else {
            $data = explode('-', current($data));
            $ano = array_shift($data);
            
            if (count($data)) $mes = array_shift($data);
            if (count($data)) $dia = array_shift($data);
        }
        
        // Coloca partes não informadas
        if (!$mes) {
            $mes = ($dataFinal) ? '12' : '01';
        }
        if (!$dia) {
            $dia = ($dataFinal) ? date('t', mktime(0,0,0,$mes,1,$ano)) : '01';
        }
        
        // Valida a data
        if (!CTimestamp::isValidDate((int) $ano, (int) $mes, (int) $dia)) {
            throw new Exception('Data inválida "' . $dia . '/' . $mes . '/' . $ano . '"');
        }
        
        // Se assegura dos zeros
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $dia = str_pad($dia, 2, '0', STR_PAD_LEFT);
        $ano = str_pad($ano, 4, '0', STR_PAD_LEFT);
        
        return $dia . '/' . $mes . '/' . $ano;
    }
}
