<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpDebt extends Model
{
    protected $table = 'tmp_debts';

	public function supplier()
	{
		return $this->belongsTo(Creditor::class, 'supplier_id', 'supplier_id');
	}
}
