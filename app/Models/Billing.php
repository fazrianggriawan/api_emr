<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Billing extends Model
{
    public function BillingById($id)
    {
        return DB::table('billing')
                ->where('id', $id)
                ->get();
    }

}
