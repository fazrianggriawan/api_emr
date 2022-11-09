<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab_nama_hasil_rujukan extends Model
{
    protected $table        = 'lab_nama_hasil_rujukan';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_nama_hasil()
    {
        return $this->hasOne(Lab_nama_hasil::class, 'id', 'id_lab_nama_hasil');
    }

    public function r_nilai_rujukan()
    {
        return $this->hasOne(Lab_nilai_rujukan::class, 'id', 'id_lab_nilai_rujukan');
    }
}
