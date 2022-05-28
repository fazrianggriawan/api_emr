<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends BaseController
{
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'MARKAS BESAR TNI ANGKATAN DARAT')->mergeCells('A1:C1');
        $sheet->setCellValue('A2', 'PANITIA KESEHATAN')->mergeCells('A2:C2');
        $sheet->setCellValue('A3', 'HASIL PEMERIKSAAN KESEHATAN')->mergeCells('A3:V3');
        $sheet->setCellValue('A4', 'ASSESSMENT DAN PEMBEKALAN CALON DANDIM TIPE "B" TNI AD TA 2022')->mergeCells('A4:V4');

        $sheet->getStyle('A3:V7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $width = 10;
        $columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V'];
        foreach ($columns as $key => $value) {
            $sheet->getStyle($value)->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension($value)->setWidth($width);
        }
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(30);

        $sheet->setCellValue('A6', 'NO')->mergeCells('A6:A7');
        $sheet->setCellValue('B6', 'SATUAN')->mergeCells('B6:B7');
        $sheet->setCellValue('C6', 'NAMA / PANGKAT / NRP / JABATAN / KESATUAN')->mergeCells('C6:C7');
        $sheet->setCellValue('D6', 'UMUM')->mergeCells('D6:M6');
        $sheet->setCellValue('D7', 'TB / BB');
        $sheet->setCellValue('E7', 'TENSI / NADI');
        $sheet->setCellValue('F7', 'PENY. DALAM');
        $sheet->setCellValue('G7', 'EKG');
        $sheet->setCellValue('H7', 'RONTGEN');
        $sheet->setCellValue('I7', 'LAB');
        $sheet->setCellValue('J7', 'THT');
        $sheet->setCellValue('K7', 'SARAF');
        $sheet->setCellValue('L7', 'KULIT');
        $sheet->setCellValue('M7', 'BEDAH');
        $sheet->setCellValue('N6', 'ATAS (A)')->mergeCells('N6:N7');
        $sheet->setCellValue('O6', 'BAWAH (B)')->mergeCells('O6:O7');
        $sheet->setCellValue('P6', 'MATA (L)')->mergeCells('P6:P7');
        $sheet->setCellValue('Q6', 'GIGI (G)')->mergeCells('Q6:Q7');
        $sheet->setCellValue('R6', 'JIWA (J)')->mergeCells('R6:R7');
        $sheet->setCellValue('S6', 'STAKES UMUM')->mergeCells('S6:S7');
        $sheet->setCellValue('T6', 'STAKES JIWA')->mergeCells('T6:T7');
        $sheet->setCellValue('U6', 'KETERANGAN')->mergeCells('U6:V7');

        $rowNumber = 8;

        $peserta = DB::table('rikkes_peserta')
                    ->leftJoin('rikkes_hasil', 'rikkes_peserta.id', '=', 'rikkes_hasil.id_rikkes_peserta')->get();

        foreach ($peserta as $key => $value) {
            $sheet->setCellValue('A'.$rowNumber, $value->noUrut);
            $sheet->setCellValue('B'.$rowNumber, '');
            $sheet->setCellValue('C'.$rowNumber, strtoupper($value->nama));
            $sheet->setCellValue('D'.$rowNumber, ($value->id_rikkes_peserta) ? $value->tinggi.' / '.$value->berat : 'TH');
            $sheet->setCellValue('E'.$rowNumber, ($value->id_rikkes_peserta) ? $value->tekananDarah.' / '.$value->nadi : 'TH');
            $sheet->setCellValue('F'.$rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('G'.$rowNumber, ($value->id_rikkes_peserta) ? $value->hasilEkg : 'TH');
            $sheet->setCellValue('H'.$rowNumber, ($value->id_rikkes_peserta) ? $value->hasilRadiologi : 'TH');
            $sheet->setCellValue('I'.$rowNumber, ($value->id_rikkes_peserta) ? $value->hasilLab : 'TH');
            $sheet->setCellValue('J'.$rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('K'.$rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('L'.$rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('M'.$rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('N'.$rowNumber, ($value->id_rikkes_peserta) ? $value->A : 'TH');
            $sheet->setCellValue('O'.$rowNumber, ($value->id_rikkes_peserta) ? $value->B : 'TH');
            $sheet->setCellValue('P'.$rowNumber, ($value->id_rikkes_peserta) ? $value->L : 'TH');
            $sheet->setCellValue('Q'.$rowNumber, ($value->id_rikkes_peserta) ? $value->G : 'TH');
            $sheet->setCellValue('R'.$rowNumber, ($value->id_rikkes_peserta) ? $value->J : 'TH');
            $sheet->setCellValue('S'.$rowNumber, ($value->id_rikkes_peserta) ? $value->stakes : 'TH');
            $sheet->setCellValue('T'.$rowNumber, ($value->id_rikkes_peserta) ? $value->J : 'TH');
            $sheet->setCellValue('U'.$rowNumber, ($value->id_rikkes_peserta) ? $value->hasil : 'TH');
            $sheet->setCellValue('V'.$rowNumber, ($value->id_rikkes_peserta) ? $value->kesimpulanPemeriksaan : 'TH');
            $rowNumber++;
        }
        $sheet->getStyle('A8:V'.$rowNumber)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('C8:C'.$rowNumber)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $rowNumber++;
        $sheet->setCellValue('A'.$rowNumber, 'KETERANGAN :')->mergeCells('A'.$rowNumber.':U'.$rowNumber);
        $rowNumber++;
        $arrayKeterangan = ['MS', 'TMS', 'TH'];
        foreach ($arrayKeterangan as $key => $value) {
            $key++;
            $sheet->setCellValue('A'.$rowNumber, $key.'.');
            $sheet->setCellValue('B'.$rowNumber, $value);
            $sheet->setCellValue('C'.$rowNumber, '');
            $sheet->setCellValue('D'.$rowNumber, 'ORANG');
            $rowNumber++;
        }

        $sheet->setCellValue('A'.$rowNumber, 'KLASIFIKASI STAKES :')->mergeCells('A'.$rowNumber.':U'.$rowNumber);
        $rowNumber++;
        $arrayKlasifikasi = ['STAKES I', 'STAKES II', 'STAKES III', 'STAKES IV', 'TIDAK HADIR'];
        foreach ($arrayKlasifikasi as $key => $value) {
            $key++;
            $sheet->setCellValue('A'.$rowNumber, $key.'.');
            $sheet->setCellValue('B'.$rowNumber, $value);
            $sheet->setCellValue('C'.$rowNumber, '');
            $sheet->setCellValue('D'.$rowNumber, 'ORANG');
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }
}
