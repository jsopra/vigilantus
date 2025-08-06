<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\helpers\models\ImovelHelper;
use app\helpers\models\MunicipioHelper;
use app\models\Municipio;
use app\models\Cliente;
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
            [['inicio', 'fim'], 'validaIntervalo'],
        ];
    }

    public function validaIntervalo($attribute, $params)
    {
        if(!$this->inicio || !$this->fim) {
            return;
        }

        $inicio = new \DateTime($this->inicio);
        $fim = new \DateTime($this->fim);

        if((abs($fim->getTimestamp() - $inicio->getTimestamp()) / 60 / 60 / 24) > 90) {
            $this->addError('inicio', 'Selecione até 90 dias para gerar o relatório');
            $this->addError('fim', 'Selecione até 90 dias para gerar o relatório');
        }
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

    public function export($cliente)
    {
        $municipio = $cliente->municipio;

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

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Vigilantus");
        $spreadsheet->getProperties()->setTitle("Relatório de Focos");

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        //cabeçalho: logo, texto prefeitura
        if($municipio->brasao) {
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
            $objDrawing->setName('Brasão de ' . $municipio->nome);
            $objDrawing->setImageResource(imagecreatefrompng(MunicipioHelper::getBrasaoUrl($municipio, 'mini')));
            $objDrawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
            $objDrawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
            $objDrawing->setCoordinates('A2');
            $objDrawing->setWorksheet($sheet);
            $objDrawing->setResizeProportional(false);
            $objDrawing->setWidth(60);
            $objDrawing->setHeight(75);
        }

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
            $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($coluna);
            $sheet->setCellValue($letraColuna . $linha, $header);
            $coluna++;
        }

        for($i = 1; $i <= $linha; $i++) {

            $letra = $i == $linha -1 ? 'A' : 'B';

            $sheet->getStyle("{$letra}{$i}:{$letraColuna}{$i}")->getFont()->setBold(true);

            if($i < $linha) {
                $sheet->mergeCells("{$letra}{$i}:{$letraColuna}{$i}");
            } else {
                $sheet->getStyle("A{$i}:{$letraColuna}{$i}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
            }

            if($letra == 'A') {
                $sheet->getStyle("{$letra}{$i}:{$letraColuna}{$i}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        //registros
        $rows = $model->all();
        foreach($rows as $row) {

            $linha++;
            $coluna = -1;

            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->bairroQuarteirao->bairro->nome);

            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->imovel_id ? ImovelHelper::getEnderecoCompleto($row->imovel) : $row->planilha_endereco);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->bairroQuarteirao->numero_quarteirao);

            if(!$modelEspecie) {
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->especieTransmissor->nome);
            }

            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->imovel_id ? $row->imovel->imovelTipo->sigla : ($row->imovelTipo ? $row->imovelTipo->sigla : null));
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->tipoDeposito->sigla);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->data_entrada);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->data_exame);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->data_coleta);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_forma_aquatica);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_forma_adulta);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(++$coluna) . $linha, $row->quantidade_ovos);

            $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($coluna);
            $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

            unset($letraColuna);
        }

        //linha com municipio, data_extração
        $linha++;
        $linha++;
        $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($coluna -1);
        $sheet->setCellValue('A' . $linha, ($municipio->nome . '/' . $municipio->sigla_estado . ', ' . date('d/m/Y')));
        $sheet->mergeCells("A{$linha}:{$letraColuna}{$linha}");
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getFont()->setBold(true);
        $sheet->getStyle("A{$linha}:{$letraColuna}{$linha}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach(range('A',$letraColuna) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getDefaultRowDimension()->setRowHeight(20);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export.xls"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }
}
