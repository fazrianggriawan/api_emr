<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab_hasil_pemeriksaan extends Model
{
    protected $table        = 'lab_hasil_pemeriksaan';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_lab_nama_hasil_rujukan()
    {
        return $this->hasOne(Lab_nama_hasil_rujukan::class, 'id', 'id_lab_nama_hasil_rujukan');
    }

}
