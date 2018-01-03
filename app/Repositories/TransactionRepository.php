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
    public function create($quantity = 1, $costPrice = 100): Transaction {
        $transaction = new Transaction([
            'id' => $this->transactions->last()->id ?? 1,
            'quantity' => $quantity,
            'created_at' => now(),
            'unit_cost_price' => $costPrice
        ]);

        $this->transactions->push($transaction);

        return $transaction;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getAllPositiveTransactions(): Collection {
        return $this->transactions->where('quantity', '>', 0);
    }

    public function getAllNegativeTransactionsCount(): int {
        return $this->transactions
            ->where('quantity', '<', 0)
            ->sum(function (Transaction $item) {
                return abs($item->getAttribute('quantity'));
            });
    }

    public function getTotalQuantityOfAllTransactions(): int {
        return $this->transactions->sum(function (Transaction $item) {
            return $item->getAttribute('quantity');
        });
    }
}