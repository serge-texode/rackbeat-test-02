<?php

namespace App\Inventory;

use App\Repositories\TransactionRepository;
use App\Transaction;
use Illuminate\Support\Collection;

class Counter
{
	protected $repository;

	public function __construct(TransactionRepository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @return int
	 */
	public function countTotalQuantity() {
		// todo return an integer representing the amount of items (quantity) left from the Repository
		return 0;
	}

	public function calculateCostPrice( $quantity = -10 ) {
		// todo return an double representing the cost price for $quantity.
		return 0.0;
	}
}