<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpDebt extends Model
{
    protected $table = 'tmp_debts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status'];

	public function supplier()
	{
		return $this->belongsTo(Creditor::class, 'supplier_id', 'supplier_id');
	}
}
