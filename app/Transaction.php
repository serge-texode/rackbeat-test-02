<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $fillable = [ 'id', 'quantity', 'unit_cost_price', 'created_at' ];
}
