<?php

namespace App\Repositories;

use App\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository
{
	public $transactions;

	public function __construct() {
		$this->transactions = new Collection();
	}

	public function get() {
		return $this->transactions;
	}

	/**
	 * @param int $quantity
	 * @param int $costPrice
	 *
	 * @return Transaction
	 */
	public function create( $quantity = 1, $costPrice = 100 ) {
		$transaction = new Transaction( [ 'id' => $this->transactions->last()->id ?? 1, 'quantity' => $quantity, 'created_at' => now(), 'cost_price' => $costPrice ] );

		$this->transactions->push( $transaction );

		return $transaction;
	}
}