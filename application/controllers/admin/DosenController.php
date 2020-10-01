<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DosenController extends CI_Controller {

    function generate_template(){
        $spreadsheet = new Spreadsheet();
        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleBody = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ],
            'borders' => [
                'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],            
        ];

        $styleFooter = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ],
            'borders' => [
                'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A2:B2')->applyFromArray($styleHeader);
        $spreadsheet->getActiveSheet()->getStyle('A13:B13')->applyFromArray($styleHeader);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);


        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'Atribut')
        ->setCellValue('B2', 'Value');

        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A13', 'No')
        ->setCellValue('B13', 'Course');

        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A3', 'Name')
        ->setCellValue('A4', 'NIP')
        ->setCellValue('A5', 'NIDN')
        ->setCellValue('A6', 'Position')
        ->setCellValue('A7', 'Filed Interest')
        ->setCellValue('A8', 'Education (Undergraduate)')
        ->setCellValue('A9', 'Education (Postgraduate)')
        ->setCellValue('A10', 'Education (Doctor)')
        ->setCellValue('A11', 'Email');

        for ($i = 3; $i < 12; $i++) {
            $spreadsheet->getActiveSheet()->getStyle('A' . $i)->applyFromArray($styleBody);
            $spreadsheet->getActiveSheet()->getStyle('B' . $i)->applyFromArray($styleBody);
        }

        $count = 1;
        for ($i = 14; $i < 24; $i++) {
            $spreadsheet->getActiveSheet()->getStyle('A' . $i)->applyFromArray($styleBody);
            $spreadsheet->getActiveSheet()->getStyle('B' . $i)->applyFromArray($styleBody);
            $spreadsheet->getActiveSheet()->getCell('A' . $i)
            ->setValueExplicit(
                $count,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC
            );
            $count++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A' . 11)->applyFromArray($styleFooter);
        $spreadsheet->getActiveSheet()->getStyle('B' . 11)->applyFromArray($styleFooter);
        $spreadsheet->getActiveSheet()->getStyle('A' . 23)->applyFromArray($styleFooter);
        $spreadsheet->getActiveSheet()->getStyle('B' . 23)->applyFromArray($styleFooter);


        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:Z1002')->getProtection()->setLocked(false);

        $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('A2:Z2')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('A3:A23')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('A12:Z12')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('A13:Z13')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('C2:Z1002')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('A23:Z2000')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

        $writer = new Xlsx($spreadsheet);

        $fileName = "Tambah-Data-Dosen-" . date("dmYhis") . ".xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function recieve_from_upload_template(){
        $fileExcel = null;
        if (isset($_FILES['fileExcel']['tmp_name'])) {
            $fileExcel = $_FILES['fileExcel']['tmp_name'];
        }

        if ($fileExcel) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($_FILES['fileExcel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $stringVal = "";
            $name = "";
            $nidn = "";
            $noHp = "-";
            for ($i = 2; $i < 11; $i++) {
                $atribut = $sheetData[$i][0];
                $value = $sheetData[$i][1];
                $stringVal .= "<tr>
                <td>".$atribut."</td>
                <td>".$value."</td>
                </tr>";

                if(2==$i){$name = $value;}
                if(4==$i){$nidn = $value;}
            }
            
            $count = 1;
            for ($i = 13; $i < 23; $i++) {            
                $course = $sheetData[$i][1];
                $stringVal .= "<tr>
                <td>".$count."</td>               
                <td>".$course."</td>               
                </tr>";
                $count++;
            }
            
            $data = array(
                'id_fakultas'=>$this->input->post('select_fakultas'),
                'username'=>"admin",
                'nm_dosen'=>$name,
                'dosen_seo'=>seo_title($name),
                'keterangan'=>$stringVal,
                'nidn'=>$nidn,
                'hp'=> $noHp
            );

            // $data = $this->model_app->insert('dosen',$data);  
            print_r($data);            
        }else{
            return false;
        }
    }

    function get_fakultas(){
        $data = $this->model_app->view('fakultas');
        $result = $data->result_array();
        echo json_encode($result);
    }
}