<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Radiologi_hasil_pemeriksaan extends Model
{
    protected $table        = 'radiologi_hasil_pemeriksaan';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_billing_head(){
        return $this->hasOne(Billing_head::class, 'id', 'id_billing_head');
    }

    public function r_pelaksana(){
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana_dokter');
    }
}
