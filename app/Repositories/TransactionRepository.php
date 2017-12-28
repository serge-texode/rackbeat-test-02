<?php

namespace App\Repositories;

use App\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository
{
	public $transactions;

	public function __construct() {
		$this->transactions = new Collection();

		$this->create( 10, 10.0 ); // Purchase
		$this->create( 20, 8.0 ); // Purchase
		$this->create( -20, null ); // Sale
		$this->create( -5, null ); // Sale
	}

	public function get() {
		return $this->transactions;
	}

	public function create( $quantity = 1, $costPrice = 100 ) {
		$transaction = new Transaction( [ 'id' => $this->transactions->last()->id ?? 1, 'quantity' => $quantity, 'created_at' => now(), 'cost_price' => $costPrice ] );

		$this->transactions->push( $transaction );

		return $transaction;
	}
}