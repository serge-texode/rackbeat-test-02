<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $fillable = [ 'id', 'quantity', 'unit_cost_price', 'created_at'];

	protected static function boot()
	{
		parent::boot();

		// todo set unit_cost_price on model "creating" event, if quantity is negative
	}

	public function calculateCostPrice() {
		// todo create this method, and use it to set the unit_cost_price
	}
}
