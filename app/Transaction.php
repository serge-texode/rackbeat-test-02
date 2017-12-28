<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $fillable = [ 'id', 'quantity', 'cost_price', 'created_at'];

	protected static function boot()
	{
		parent::boot();

		// todo set cost_price on model "creating" event, if quantity is positive
	}

	public function calculateCostPrice() {
		// todo create this method, and use it to set the cost_price
	}
}
