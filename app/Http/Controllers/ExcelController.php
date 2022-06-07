<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];
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
        $sheet->setCellValue('D7', 'TB / BB / IMT');
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
        $sheet->setCellValue('P6', 'DENGAR (D)')->mergeCells('P6:P7');
        $sheet->setCellValue('Q6', 'MATA (L)')->mergeCells('Q6:Q7');
        $sheet->setCellValue('R6', 'GIGI (G)')->mergeCells('R6:R7');
        $sheet->setCellValue('S6', 'JIWA (J)')->mergeCells('S6:S7');
        $sheet->setCellValue('T6', 'STAKES UMUM')->mergeCells('T6:T7');
        $sheet->setCellValue('U6', 'KETERANGAN')->mergeCells('U6:V7');

        $rowNumber = 8;

        $peserta = DB::table('rikkes_peserta')
            ->select('rikkes_peserta.id as id_rikkes_peserta','rikkes_peserta.jnsKelamin','rikkes_peserta.noUrut','rikkes_peserta.noPeserta','rikkes_peserta.nama','anamnesa','tinggi','berat','imt','tekananDarah','nadi','tubuhBentuk','tubuhGerak','kepala','muka','leher','mata','od1','od2','od3','os1','os2','os3','campus','kenalWarna','lainLain','telinga','ad','as','tajamPend','membranTymp','penyTel','hidung','tenggorokan','gigiMulut','gigiD','gigiM','gigiF','karang','protesa','penyMulut','thoraxPernafasan','thoraxBentuk','cor','pulmo','abdomen','lien','hepar','regioInguinalis','genitalia','perineum','angGerakAtas','angGerakBawah','kulit','refleks','hasilLab','rikkes_hasil_ekg.hasil as hasilEkg','rikkes_hasil_radiologi.keterangan as hasilRadiologi','hasilAudiometri','rikkes_hasil_psikometri.hasil as hasilPsikometriKode','rikkes_hasil_psikometri.keterangan','rikkes_hasil_psikometri.pleton','odontogramIdentifikasi','kesimpulanPemeriksaan','A','B','D','G','J','L','U','stakes','rikkes_hasil_3.hasil as hasilStakes','rikkes_hasil_3.hasilEkg as hasilEkgForm')
            ->leftJoin('rikkes_hasil_1', 'rikkes_peserta.id', '=', 'rikkes_hasil_1.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_2', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_2.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_3', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_3.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_ekg', 'rikkes_peserta.noUrut', '=', 'rikkes_hasil_ekg.noUrut')
            ->leftJoin('rikkes_hasil_psikometri', 'rikkes_peserta.noUrut', '=', 'rikkes_hasil_psikometri.noUrut')
            ->leftJoin(DB::raw('(select * from rikkes_hasil_radiologi where active = 1) as rikkes_hasil_radiologi'), 'rikkes_peserta.id', '=', 'rikkes_hasil_radiologi.id_rikkes_peserta')
            ->get();

        foreach ($peserta as $key => $value) {
            $radiologi = strstr(strtolower(strip_tags($value->hasilRadiologi)), 'kesan');
            $bmi = $this->hitungBmi($value->imt, $value->jnsKelamin);
            if( $value->mata == 'L1' ){
                $mata = 'ODS '.$value->od1.' '.$value->od2.' '.$value->od3;
                $mata .= '          '.$value->mata;
            }else{
                $mata = 'VOD '.$value->od1.' '.$value->od2.' '.$value->od3;
                $mata .= '          ';
                $mata .= 'VOS '.$value->os1.' '.$value->os2.' '.$value->os3;
                $mata .= '          '.$value->mata;
            }

            if( $value->hasilLab == '' || strtolower($value->hasilLab) == 'tak' || strtolower($value->hasilLab) == 'dbn' || strtolower($value->hasilLab) == 'normal' ){
                $hasilLab = $value->hasilLab.' (U1)';
            }else{
                $hasilLab = $value->hasilLab;
            }

            if( $value->hasilEkgForm != '' ){
                $hasilEkgForm = $value->hasilEkgForm;
            }else{
                $hasilEkgForm = $value->hasilEkg;
            }

            if( $hasilEkgForm == '' || strtolower($hasilEkgForm) == 'tak' || strtolower($hasilEkgForm) == 'dbn' || strtolower($hasilEkgForm) == 'normal' ){
                $hasilEkg = $hasilEkgForm.' (U1)';
            }else{
                $hasilEkg = $hasilEkgForm;
            }

            if( $value->hasilAudiometri == '' || strtolower($value->hasilAudiometri) == 'tak' || strtolower($value->hasilAudiometri) == 'dbn' || strtolower($value->hasilAudiometri) == 'normal' ){
                $hasilAudiometri = $value->hasilAudiometri.' (U1)';
            }else{
                $hasilAudiometri = $value->hasilAudiometri;
            }

            $tht = $value->telinga.' '.$value->ad.' '.$value->as.' '.$value->tajamPend.' '.$value->membranTymp.' '.$value->penyTel.' '.$value->hidung.' '.$value->tenggorokan;
            $tht = str_replace('NORMAL','',strtoupper($tht));

            $bedah = $value->regioInguinalis.' '.$value->genitalia.' '.$value->perineum;
            $bedah = str_replace('NORMAL','',strtoupper($bedah));
            $bedah = str_replace('DBN','',strtoupper($bedah));
            $bedah = str_replace('TAK','',strtoupper($bedah));

            $sheet->setCellValue('A' . $rowNumber, $value->noUrut);
            $sheet->setCellValue('B' . $rowNumber, '');
            $sheet->setCellValue('C' . $rowNumber, strtoupper($value->nama));
            $sheet->setCellValue('D' . $rowNumber, ($value->id_rikkes_peserta) ? $value->tinggi . ' cm / ' . $value->berat . ' kg'.' / '.$value->imt.' ('.$bmi.')' : 'TH');
            $sheet->setCellValue('E' . $rowNumber, ($value->id_rikkes_peserta) ? $value->tekananDarah . ' / ' . $value->nadi : 'TH');
            $sheet->setCellValue('F' . $rowNumber, ($value->id_rikkes_peserta) ? '' : 'TH');
            $sheet->setCellValue('G' . $rowNumber, ($value->id_rikkes_peserta) ? $hasilEkg : 'TH');
            $sheet->setCellValue('H' . $rowNumber, ($value->id_rikkes_peserta) ? $radiologi : 'TH');
            $sheet->setCellValue('I' . $rowNumber, ($value->id_rikkes_peserta) ? $hasilLab : 'TH');
            $sheet->setCellValue('J' . $rowNumber, ($value->id_rikkes_peserta) ? strtoupper($tht) : 'TH');
            $sheet->setCellValue('K' . $rowNumber, ($value->id_rikkes_peserta) ? $value->refleks : 'TH');
            $sheet->setCellValue('L' . $rowNumber, ($value->id_rikkes_peserta) ? $value->kulit : 'TH');
            $sheet->setCellValue('M' . $rowNumber, ($value->id_rikkes_peserta) ? strtoupper($bedah) : 'TH');
            $sheet->setCellValue('N' . $rowNumber, ($value->id_rikkes_peserta) ? $value->angGerakAtas : 'TH');
            $sheet->setCellValue('O' . $rowNumber, ($value->id_rikkes_peserta) ? $value->angGerakBawah : 'TH');
            $sheet->setCellValue('P' . $rowNumber, ($value->id_rikkes_peserta) ? $hasilAudiometri : 'TH');
            $sheet->setCellValue('Q' . $rowNumber, ($value->id_rikkes_peserta) ? $mata : 'TH');
            $sheet->setCellValue('R' . $rowNumber, ($value->id_rikkes_peserta) ? $value->odontogramIdentifikasi.' '.$value->gigiMulut : 'TH');
            $sheet->setCellValue('S' . $rowNumber, ($value->id_rikkes_peserta) ? $value->hasilPsikometriKode : 'TH');
            $sheet->setCellValue('T' . $rowNumber, ($value->id_rikkes_peserta) ? $value->U : 'TH');
            $sheet->setCellValue('U' . $rowNumber, ($value->id_rikkes_peserta) ? $value->hasilStakes : 'TH');
            $sheet->setCellValue('V' . $rowNumber, ($value->id_rikkes_peserta) ? $value->kesimpulanPemeriksaan : 'TH');
            $rowNumber++;
        }
        $sheet->getStyle('A8:V' . $rowNumber)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('C8:C' . $rowNumber)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $rowNumber++;
        $sheet->setCellValue('A' . $rowNumber, 'KETERANGAN :')->mergeCells('A' . $rowNumber . ':U' . $rowNumber);
        $rowNumber++;
        $arrayKeterangan = ['MS', 'TMS', 'TH'];
        foreach ($arrayKeterangan as $key => $value) {
            $key++;
            $sheet->setCellValue('A' . $rowNumber, $key . '.');
            $sheet->setCellValue('B' . $rowNumber, $value);
            $sheet->setCellValue('C' . $rowNumber, '');
            $sheet->setCellValue('D' . $rowNumber, 'ORANG');
            $rowNumber++;
        }

        $sheet->setCellValue('A' . $rowNumber, 'KLASIFIKASI STAKES :')->mergeCells('A' . $rowNumber . ':U' . $rowNumber);
        $rowNumber++;
        $arrayKlasifikasi = ['STAKES I', 'STAKES II', 'STAKES III', 'STAKES IV', 'TIDAK HADIR'];
        foreach ($arrayKlasifikasi as $key => $value) {
            $key++;
            $sheet->setCellValue('A' . $rowNumber, $key . '.');
            $sheet->setCellValue('B' . $rowNumber, $value);
            $sheet->setCellValue('C' . $rowNumber, '');
            $sheet->setCellValue('D' . $rowNumber, 'ORANG');
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Summary-Data-Rikkes.xls"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

        // $writer->save('hello world.xlsx');
    }

    public function exportAllData()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'MARKAS BESAR TNI ANGKATAN DARAT')->mergeCells('A1:C1');
        $sheet->setCellValue('A2', 'PANITIA KESEHATAN')->mergeCells('A2:C2');
        $sheet->setCellValue('A3', 'HASIL PEMERIKSAAN KESEHATAN')->mergeCells('A3:J3');
        $sheet->setCellValue('A4', 'ASSESSMENT DAN PEMBEKALAN CALON DANDIM TIPE "B" TNI AD TA 2022')->mergeCells('A4:J4');

        $width = 18;
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(30);

        $sheet->setCellValue('A6', 'NO.URUT');
        $sheet->setCellValue('B6', 'NO.PESERTA');
        $sheet->setCellValue('C6', 'NAMA');
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getStyle('C')->getAlignment()->setWrapText(true);

        $lastKey = 3;
        $columns = DB::getSchemaBuilder()->getColumnListing('rikkes_hasil_1');
        foreach ($columns as $key => $value) {
            $colName = $this->getNameFromNumber($lastKey);
            if( $value != 'dateCreated' && $value != 'id' && $value != 'id_rikkes_peserta' ){
                $sheet->setCellValue($colName . '6', strtoupper($value));
                $sheet->getStyle($colName)->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension($colName)->setWidth($width);
                $lastKey++;
            }
        }

        $columns = DB::getSchemaBuilder()->getColumnListing('rikkes_hasil_2');
        foreach ($columns as $key => $value) {
            $colName = $this->getNameFromNumber($lastKey);
            if( $value != 'dateCreated' && $value != 'id' && $value != 'id_rikkes_peserta' ){
                $sheet->setCellValue($colName . '6', strtoupper($value));
                $sheet->getStyle($colName)->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension($colName)->setWidth($width);
                $lastKey++;
            }
        }

        $columns = DB::getSchemaBuilder()->getColumnListing('rikkes_hasil_3');
        foreach ($columns as $key => $value) {
            $colName = $this->getNameFromNumber($lastKey);
            if( $value != 'dateCreated' && $value != 'id' && $value != 'id_rikkes_peserta' ){
                $sheet->setCellValue($colName . '6', strtoupper($value));
                $sheet->getStyle($colName)->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension($colName)->setWidth($width);
                $lastKey++;
            }
        }

        $rowNumber = 7;

        $peserta = DB::table('rikkes_peserta')
            ->select('rikkes_peserta.noUrut','rikkes_peserta.noPeserta','rikkes_peserta.nama','anamnesa','tinggi','berat','imt','tekananDarah','nadi','tubuhBentuk','tubuhGerak','kepala','muka','leher','mata','od1','od2','od3','os1','os2','os3','campus','kenalWarna','lainLain','telinga','ad','as','tajamPend','membranTymp','penyTel','hidung','tenggorokan','gigiMulut','gigiD','gigiM','gigiF','karang','protesa','penyMulut','thoraxPernafasan','thoraxBentuk','cor','pulmo','abdomen','lien','hepar','regioInguinalis','genitalia','perineum','angGerakAtas','angGerakBawah','kulit','refleks','hasilLab','rikkes_hasil_ekg.hasil','rikkes_hasil_radiologi.keterangan as hasilRadiologi','hasilAudiometri','rikkes_hasil_psikometri.hasil as hasilPsikometriKode','rikkes_hasil_psikometri.keterangan','rikkes_hasil_psikometri.pleton','odontogramIdentifikasi','kesimpulanPemeriksaan','A','B','D','G','J','L','U','stakes')
            ->leftJoin('rikkes_hasil_1', 'rikkes_peserta.id', '=', 'rikkes_hasil_1.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_2', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_2.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_3', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_3.id_rikkes_peserta')
            ->leftJoin('rikkes_hasil_ekg', 'rikkes_peserta.noUrut', '=', 'rikkes_hasil_ekg.noUrut')
            ->leftJoin('rikkes_hasil_psikometri', 'rikkes_peserta.noUrut', '=', 'rikkes_hasil_psikometri.noUrut')
            ->leftJoin(DB::raw('(select * from rikkes_hasil_radiologi where active = 1) as rikkes_hasil_radiologi'), 'rikkes_peserta.id', '=', 'rikkes_hasil_radiologi.id_rikkes_peserta')
            ->get();

        foreach ($peserta as $key => $value) {
            $i = 0;
            foreach ($value as $key2 => $value2) {
                $colName = $this->getNameFromNumber($i);
                if( $key2 == 'hasilRadiologi' ){
                    $value2 = strstr(strtolower(strip_tags($value2)), 'kesan');
                }
                $sheet->setCellValue($colName . $rowNumber, strip_tags($value2));
                $i++;
            }
            $rowNumber++;
        }

        $sheet->getStyle('A3:'.$colName.'6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$rowNumber.':'.$colName.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $writer = new Xlsx($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Summary-Data-Rikkes-All-Data.xls"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

        // $writer->save('hello world.xlsx');
    }

    public function getNameFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    public function hitungBmi($bmi, $jnsKelamin) {
        $hasilBmi = '';
        if (strtolower($jnsKelamin) == 'perempuan') {
            if ($bmi >= 19 && $bmi <= 23.9) {
                $hasilBmi = 'U1';
            } else if ($bmi >= 24 && $bmi <= 25.9) {
                $hasilBmi = 'U2';
            } else if ($bmi >= 18.5 && $bmi <= 18.9) {
                $hasilBmi = 'U2';
            } else if ($bmi >= 26 && $bmi <= 28.9) {
                $hasilBmi = 'U3';
            } else if ($bmi >= 15 && $bmi <= 18.4) {
                $hasilBmi = 'U3';
            } else if ($bmi >= 29 || $bmi <= 14.9) {
                $hasilBmi = 'U4';
            }
        }

        if (strtolower($jnsKelamin) == 'laki-laki') {
            if ($bmi >= 20 && $bmi <= 24.9) {
                $hasilBmi = 'U1';
            } else if ($bmi >= 18.5 && $bmi <= 19.9) {
                $hasilBmi = 'U2';
            } else if ($bmi >= 15 && $bmi <= 18.4) {
                $hasilBmi = 'U2';
            } else if ($bmi >= 27 && $bmi <= 29.9) {
                $hasilBmi = 'U3';
            } else if ($bmi >= 30 || $bmi <= 14.9) {
                $hasilBmi = 'U4';
            }
        }
        return $hasilBmi;
    }
}
