<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\helpers\models\ImovelHelper;
use app\models\Municipio;
use Yii;

class FocosExcelReport extends Model
{
    /*
     * filtros
     */
    public $bairro_id;
    public $especie_transmissor_id;
    public $inicio;
    public $fim;

    public function rules()
    {
        return [
            [['inicio', 'fim'], 'required'],  
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id'],
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
            [['inicio', 'fim'], 'date'],   
        ];
    }

    public function attributeLabels()
    {
        return [
            'bairro_id' => 'Bairro',
            'lira' => 'LIRA',
            'especie_transmissor_id' => 'Espécie de Transmissor',
            'inicio' => 'Início Entrada',
            'fim' => 'Fim Entrada',
        ];
    }
    
    public function export() 
    {
        $municipio = Municipio::find()->one(); //FIX
        
        $model = FocoTransmissor::find();
        
        if($this->bairro_id) {
            $model->doBairro($this->bairro_id);
        }
        
        $modelEspecie = null;
        if($this->especie_transmissor_id) {
            $model->daEspecieDeTransmissor($this->especie_transmissor_id);
            
            $modelEspecie = EspecieTransmissor::find()->andWhere(['id' => $this->especie_transmissor_id])->one();
        }
        
        $model->dataEntradaEntre($this->inicio, $this->fim); 
        
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Vigilantus");
        $objPHPExcel->getProperties()->setTitle("Relatório de Focos");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        
        //cabeçalho: logo, texto prefeitura
        $linha = 1;
        
        $objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('Brasão de ' . $municipio->nome);
        $objDrawing->setImageResource(imagecreatefromjpeg(Yii::getAlias('@webroot') . '/img/brasao/SC/chapeco.jpg')); //@todo
        $objDrawing->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
        $objDrawing->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setCoordinates('A2');
        $objDrawing->setWorksheet($sheet);
        $objDrawing->setResizeProportional(false);
        $objDrawing->setWidth(60);
        $objDrawing->setHeight(75);
        
        $textoCabecalho = [
            'Prefeitura Municipal de ' . $municipio->nome,
            'Secretaria Municipal de Saúde',
            'Vigilância em Saúde/Vigilância Ambiental',
            'Programa de Controle da Dengue',
        ];
        
        $linha = 0;
        $coluna = 0;
        
        foreach($textoCabecalho as $header) {
            ++$linha;
            $sheet->setCellValue('B' . $linha, $header);
            $coluna++;
        }
        
        $linha++;
        
        $sheet->mergeCells("A1:A{$linha}");
        
        $linha++;
        $coluna = 0;
        
        //linha relatorio de focos titulo
        $sheet->setCellValue('A' . $linha, 'Relatório de Focos ' . ($modelEspecie ? ' ' . $modelEspecie->nome : ''));
        
        //linha header tabela
        $headers = ['Bairro', 'Endereço', 'Quarteirão'];
        
        if(!$modelEspecie) {
            array_push($headers,'Espécie');
        }
        
        array_push($headers, 'Tipo Imóvel', 'Tipo Depósito', 'Data Entrada', 'Data Exame', 'Data Coleta', 'Nº F. Aquática', 'Nº F. Adulta', 'Nº F. Ovos');
        
        $linha++;
        $coluna = 0;

        foreach($headers as $header) {
            $letraColuna = \PHPExcel_Cell::stringFromColumnIndex($coluna);
            $sheet->setCellValue($letraColuna . $linha, $header);
            $coluna++;
        }
        
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getFont()->setBold(true);
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THICK);
                     
        $letraColuna = \PHPExcel_Cell::stringFromColumnIndex($coluna - 1);
        
        $linhaMerge = $linha;
        
        while($linhaMerge > 0) {
            
            $linhaMerge = $linhaMerge -1;
            
            $letra = $linhaMerge == $linha - 1 ? 'A' : 'B';
            
            $sheet->mergeCells("{$letra}{$linhaMerge}:{$letraColuna}{$linhaMerge}");
            $sheet->getStyle("{$letra}{$linhaMerge}:{$letraColuna}{$linhaMerge}")->getFont()->setBold(true);
            
            if($letra == 'A') {
                $sheet->getStyle("{$letra}{$linhaMerge}:{$letraColuna}{$linhaMerge}")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }
        
        unset($linhaMerge);
        
        //registros
        $rows = $model->all();
        foreach($rows as $row) {
            
            $linha++;
            $coluna = -1;
            
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->bairroQuarteirao->bairro->nome);
            
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->imovel_id ? ImovelHelper::getEnderecoCompleto($row->imovel) : $row->planilha_endereco);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->bairroQuarteirao->numero_quarteirao);
            
            if(!$modelEspecie) {
                $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->especieTransmissor->nome);
            }
            
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->imovel_id ? $row->imovel->imovelTipo->sigla : ($row->imovelTipo ? $row->imovelTipo->sigla : null));
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->tipoDeposito->sigla);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->data_entrada);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->data_exame);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->data_coleta);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_forma_aquatica);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_forma_adulta);
            $sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_ovos);
            
            $letraColuna = \PHPExcel_Cell::stringFromColumnIndex($coluna);
            $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THICK);
        
            unset($letraColuna);
        }

        //linha com Chapecó, data_extração
        $linha++;
        $linha++;
        $letraColuna = \PHPExcel_Cell::stringFromColumnIndex($coluna);
        $sheet->setCellValue('A' . $linha, ($municipio->nome . '/' . $municipio->sigla_estado . ', ' . date('d/m/Y')));
        $sheet->mergeCells("A{$linha}:{$letraColuna}{$linha}");
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getFont()->setBold(true);
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        foreach(range('A',$letraColuna) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        $sheet->getDefaultStyle()->getFont()->setName('Arial');
        $sheet->getDefaultStyle()->getFont()->setSize(10); 
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="export.xls"'); 
        header('Cache-Control: max-age=0'); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
        $objWriter->save('php://output');
    }
}
