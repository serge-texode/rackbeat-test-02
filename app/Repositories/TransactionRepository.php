<?php

namespace App\Repositories;

use App\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository
{
	public $transactions;

	public function __construct() {
		$this->transactions = new Collection();

		$this->transactions->push( new Transaction( [ 'quantity' => 10 ] ) ); // Purchase
		$this->transactions->push( new Transaction( [ 'quantity' => 20 ] ) ); // Purchase
		$this->transactions->push( new Transaction( [ 'quantity' => -20 ] ) ); // Sale
		$this->transactions->push( new Transaction( [ 'quantity' => 10 ] ) ); // Purchase
	}

	public function get() {
		return $this->transactions;
	}

	public function create( $quantity = 1, $costPrice = 100 ) {
		$transaction = new Transaction( [ 'id' => $this->transactions->last()->id, 'quantity' => $quantity, 'created_at' => now(), 'cost_price' => $costPrice ] );

		$this->transactions->push( $transaction );

		return $transaction;
	}
}