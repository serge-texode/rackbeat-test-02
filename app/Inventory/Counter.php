<?php
namespace App\Inventory;

use App\Repositories\TransactionRepository;

class Counter
{
    protected $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function countTotalQuantity(): int {
        return $this->repository->getTotalQuantityOfAllTransactions();
    }

    public function calculateCostPrice(int $quantity = -10): float {

        if ($quantity >= 0) {
            throw new \InvalidArgumentException(sprintf('Quantity %s should be negative value', $quantity));
        }

        $quantity = abs($quantity);

        if ($quantity > ($totalQuantity = $this->repository->getTotalQuantityOfAllTransactions())) {
            throw new \InvalidArgumentException(sprintf('Quantity %s should be less or equals than %s', $quantity, $totalQuantity));
        }

        $positiveTransactions = $this->repository->getAllPositiveTransactions();
        $negativeTransactionsQuantity = $this->repository->getAllNegativeTransactionsCount();

        $totalQuantity = 0;
        $totalCostPrice = 0;

        $calculatePreviousNegativeTransactions = $negativeTransactionsQuantity > 0;

        foreach ($positiveTransactions as $positiveTransaction) {

            $positiveTransactionQuantity = $positiveTransaction->getAttribute('quantity');
            $positiveTransactionCostPrice = $positiveTransaction->getAttribute('unit_cost_price');

            if ($calculatePreviousNegativeTransactions) {
                $negativeTransactionsQuantity -= $positiveTransactionQuantity;

                if ($negativeTransactionsQuantity > 0) {
                    continue;
                }

                $quantityRemainder = abs($negativeTransactionsQuantity);

                if ($quantity <= $quantityRemainder) {
                    $totalQuantity = $positiveTransactionQuantity;
                    $totalCostPrice = $positiveTransactionCostPrice * $totalQuantity;
                    break;
                }

                $totalQuantity = $quantityRemainder;
                $totalCostPrice = $quantityRemainder * $positiveTransactionCostPrice;

                $calculatePreviousNegativeTransactions = false;
                continue;
            }

            $totalQuantity += $positiveTransactionQuantity;

            if ($quantity > $totalQuantity) {
                $totalCostPrice += $positiveTransactionQuantity * $positiveTransactionCostPrice;
            } else {
                $quantityRemainder = $totalQuantity - $quantity;
                $totalQuantity -= $quantityRemainder;
                $totalCostPrice += ($positiveTransactionQuantity - $quantityRemainder) * $positiveTransactionCostPrice;
                break;
            }
        }

        return round($totalCostPrice / $totalQuantity, 2);
    }
}