<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'nrhosp_acc_com_bank';
    protected $primaryKey = 'bank_acc_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    
    public function payment()
  	{
      	return $this->hasMany('App\Models\Payment', 'bank_acc_id', 'bank_acc_id');
  	}
}
