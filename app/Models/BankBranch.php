<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    protected $table = 'nrhosp_acc_bank_branch';
    protected $primaryKey = 'bank_branch_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    
    public function bankAcc()
  	{
      	return $this->hasMany('App\Models\BankAccount', 'bank_branch_id', 'bank_branch_id');
    }
    
    public function bank()
  	{
      	return $this->belongsTo('App\Models\Bank', 'bank_id', 'bank_id');
  	}
}
