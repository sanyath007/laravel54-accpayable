<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpDebt extends Model
{
    protected $table = 'tmp_debts';

	public function debttype()
	{
		return $this->belongsTo(DebtType::class, 'debt_type_id', 'debt_type_id');
	}

	public function supplier()
	{
		return $this->belongsTo(Creditor::class, 'supplier_id', 'supplier_id');
	}
}
