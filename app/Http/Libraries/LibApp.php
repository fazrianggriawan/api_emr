<?php

namespace App\Http\Libraries;

class LibApp
{
    public static function response($code = 200, $data = array(), $message = '')
    {
        $array = array(
            'code' => $code,
            'data' => $data,
            'message' => $message
        );
        return \json_encode($array);
    }

    public static function response_success($data = array())
    {
        response();
        $array = array(
            'code' => 200,
            'data' => $data,
            'message' => 'Sukses'
        );
        return \json_encode($array);
    }

    public static function dateHuman($tanggal = '')
    {
        $arrayBulan = array(
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'Mei',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ags',
            '09' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des',
        );

        if ($tanggal != '') {
            $aTanggal = explode('-', $tanggal);
            return $aTanggal[2] . ' ' . $arrayBulan[$aTanggal[1]] . ' ' . $aTanggal[0];
        }
    }

    public static function dateLocalToSql($tanggal = '')
    {
        if ($tanggal != '') {
            $aTanggal = explode('/', $tanggal);
            return $aTanggal[2] . '-' . $aTanggal[1] . '-' . $aTanggal[0];
        }
    }

    public static function getAge($date = '')
    {
        //date in mm/dd/yyyy format; or it can be in other formats as well
        $birthDate = $date;
        //explode the date to get month, day and year
        $birthDate = explode("-", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
            ? ((date("Y") - $birthDate[0]) - 1)
            : (date("Y") - $birthDate[0]));
        return $age;
    }

    public static function terbilang($x, $sen = FALSE)
    {
        $a = substr($x, 0, 1);
        if ($a == '-') {
            return '';
            exit;
        }

        if ($sen == FALSE) {
            $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        } else {
            $abil = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        }

        if ($x < 12)
            if ($sen == TRUE) {
                $len = strlen($x);
                $b = substr($x, 0, 1);

                if ($len == 2 && $b == '0') {
                    $f = "";
                    for ($i = 0; $i < $len; $i++) {
                        $s = substr($x, $i, 1);
                        $f .= " " . $abil[$s];
                    }
                    return $f;
                } else {
                    return " " . $abil[$x];
                }
            } else {
                return " " . $abil[$x];
            }
        elseif ($x < 20)
            return self::Terbilang($x - 10) . " belas";
        elseif ($x < 100)
            return self::Terbilang($x / 10) . " puluh" . self::Terbilang($x % 10);
        elseif ($x < 200)
            return " seratus" . self::Terbilang($x - 100);
        elseif ($x < 1000)
            return self::Terbilang($x / 100) . " ratus" . self::Terbilang($x % 100);
        elseif ($x < 2000)
            return " seribu" . self::Terbilang($x - 1000);
        elseif ($x < 1000000)
            return self::Terbilang($x / 1000) . " ribu" . self::Terbilang($x % 1000);
        elseif ($x < 1000000000)
            return self::Terbilang($x / 1000000) . " juta" . self::Terbilang($x % 1000000);
        elseif ($x < 1000000000000)
            return self::Terbilang($x / 1000000000) . " milyar" . self::Terbilang($x % 1000000000);
    }
}
