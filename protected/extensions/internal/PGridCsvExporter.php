<?php
class PGridCsvExporter extends CComponent
{
    /**
     * @var FGridView
     */
    public $grid;

    /**
     * @var string
     */
    public $receiverEmail;

    /**
     * @param FGridView $grid
     */
    public function __construct(PGridView $grid = null, $receiverEmail = null)
    {
        $this->grid = $grid;
        $this->receiverEmail = $receiverEmail;
    }

    /**
     * @return array
     */
    protected function _getHeader()
    {
        $header = array();
        $grid = $this->grid;
        
        foreach ($grid->columns as $column) {
            
            if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn', 'PModalColumn'))) {
                continue;
            }
            
            $name = null;
            
            if ($column->header) {
                $name = $column->header;
            }
            elseif ($grid->dataProvider instanceof CActiveDataProvider) {
                $name = $grid->dataProvider->model->getAttributeLabel($column->name);
            }
            elseif ($column->name) {
                $name = $column->name;
            }
            
            $header[] = $name;
        }
        
        // Corrige bug com o Excel quando a primeira coluna se chama ID
        if (isset($header[0]) && strtoupper($header[0]) == 'ID') {
            $header[0] = ' ' . $header[0];
        }

        return $header;
    }

	/**
     * @return array
     */
    protected function _getFooter()
    {
        $footer = array();
        $grid = $this->grid;
        
        $qtde = 0;
        
        foreach ($grid->columns as $column) {
            
            if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn', 'PModalColumn')))
                continue;
            
            $name = null;
            if ($column->footer) {
                $name = $column->footer;
                $qtde++;
            }
            else
				$name = ' ';
            
            $footer[] = $name;
        }

        return $qtde > 0 ? $footer : null;
    }

    /**
     * Roda processo em background que envia o CSV deste grid por e-mail
     * @return void
     */
    public function registerJob()
    {
        if (!extension_loaded('gearman') || !Yii::app()->gearman) {
            throw new Exception('Essa instalação do Yii não suporta exportar grids em segundo plano!');
        }

        $urlArquivo = str_replace(
            Yii::app()->baseUrl,
            Yii::app()->getBaseUrl(true),
            Yii::app()->assetManager->baseUrl
        );

        ob_clean();

        // Não repete requisições de relatórios:
        // - na mesma hora
        // - na mesma tela
        // - no mesmo grid
        // - pro mesmo usuário
        $requestKey = md5(
            date('YmdH') .
            $_SERVER['REQUEST_URI'] .
            $this->grid->id .
            ((!Yii::app()->user || Yii::app()->user->isGuest) ? '' : Yii::app()->user->id)
        );

        $fileName = $this->grid->exportFileName . '-' . $requestKey . '.csv';
        $filePath = Yii::app()->assetManager->basePath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            die('_JOB_REPETIDO_');
        }
        elseif (file_exists($filePath . '.zip')) {
            die('_JOB_FINALIZADO_');
        }

        BackgroundJob::client(
            'PCsvExporterJob',
            array(
                'file_name'   => $fileName,
                'file_path'   => Yii::app()->assetManager->basePath,
                'file_url'    => $urlArquivo,
                'email'       => $this->receiverEmail,
                'data'        => Date::getDateTime(),
                'grid'        => serialize($this->grid),
            )
        );

        die('_JOB_REGISTRADO_');
    }

    /**
     * Exporta os dados do grid em um arquivo CSV printado na saída padrão do PHP
     * @return void
     */
    public function stream()
    {
        ob_clean();

        if ($this->grid->debug) {
            header("Content-type: text/html; charset=" . Yii::app()->charset);
            file_put_contents('php://output', '<pre>');
        }
        else {
            header("Content-Type: application/csv; charset=" . Yii::app()->charset);
            header('Content-Disposition: attachment; filename="' . $this->grid->exportFileName . '_' . date('YmdHis') . '.csv"');
        }

        $this->export('php://output');
        
        exit;
    }

    /**
     * Exporta os dados do grid em um arquivo CSV printado na saída padrão do PHP
     * @param string $file
     * @return void
     */
    public function export($file)
    {
        // Sem limite de tempo
        set_time_limit(0);

        $grid = $this->grid;
        
        // Monta header
        $header = $this->_getHeader();

        // Desativa os logs até o final do script, evitando que consumam memória do PHP
        $enabledLogs = array();
        $previousAutoFlush = Yii::getLogger()->autoFlush;
        Yii::getLogger()->autoFlush = 1;

        if (!empty(Yii::app()->log) && !empty(Yii::app()->log->routes)) {
            foreach (Yii::app()->log->routes as $route) {
                if ($route->enabled)
                    $route->enabled = false;
            }
        }
        
        // Abre pra escrever na tela
        $handle = fopen($file, 'w');
        
        // Imprime cabeçalho
        fputcsv($handle, $header, ';');
        
        // Seta em quantos registros por vez ele carrega (pra não estourar a memória)
        $pagination = $grid->dataProvider->getPagination();
        $pagination->setPageSize($grid->recordsLoadingStep);
        
        $steps = $pagination->getPageCount();
        
        for ($currentStep = 0; $currentStep < $steps; $currentStep++) {
            
            // Muda bloco atual
            $pagination->setCurrentPage($currentStep);
            
            // Obtém dados
            $rows = $grid->dataProvider->getData(true);
        
            // Monta o CSV
            foreach ($rows as $data) {

                $row = array();

                foreach ($grid->columns as $column) {

                    if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn', 'PModalColumn'))) {
                        continue;
                    }

                    $value = null;

                    if ($column->value !== null) {

                        $value = $this->_evaluateColumnExpression($column->value, array('data'=>$data));
                    }
                    else if ($column->name !== null) {
                        $value = CHtml::value($data, $column->name);
                    }

					$strippedValue = strip_tags(($value === null) ? '' : $grid->getFormatter()->format($value, $column->type));

					if(property_exists($column, 'exportAsString') && $column->exportAsString && is_numeric($strippedValue))
						$strippedValue = "'" . $strippedValue;

                    $row[] = $strippedValue;
                }

                // Imprime na tela
                fputcsv($handle, $row, ';');
            }
        }
        
        // Monta footer, se houver
        $footer = $this->_getFooter();
        if($footer)
			fputcsv($handle, $footer, ';');

        // Encerra
        fclose($handle);

        // Reativa logs
        foreach ($enabledLogs as $route) {
            $route->enabled = true;
        }

        Yii::getLogger()->autoFlush = $previousAutoFlush;
    }

    /**
     * Converte uma expressão do GRID em uma string 
     * @return string
     */
    protected function _evaluateColumnExpression($_expression_, $_data_ = array())
    {
        extract($_data_);
        return eval('return '.$_expression_.';');
    }

    protected static function retornaValorInput($html, $buscaPlaceHolder = false) {
        $value = '';
        $dom = new DOMDocument();
        if ($dom->loadHTML($html)) {
            $xp = new DOMXpath($dom);
            $nodes = $xp->query('//input');
            foreach ($nodes as $node) {
                if ($node->getAttribute('value')) {
                    $value = $node->getAttribute('value');
                } elseif($buscaPlaceHolder) {
                    $value = $node->getAttribute('placeholder');
                }
            }
        }
        return $value;
    }
}
