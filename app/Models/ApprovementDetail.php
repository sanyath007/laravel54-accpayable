<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovementDetail extends Model
{
	protected $table = 'nrhosp_acc_app_detail';
	
	protected $primaryKey = 'app_detail_id';
	
	public $incrementing = false; //ไม่ใช้ options auto increment
	
	public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
	
	protected $fillable = ['debt_id', 'ref_debt'];
	
	public function debts()
	{
		return $this->belongsTo('App\Models\Debt', 'debt_id', 'debt_id');
	}

	public function approve()
	{
		return $this->belongsTo('App\Models\Approvement', 'app_id', 'app_id');
	}
}
