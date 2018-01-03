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

        // Process every positive transaction in chronology order
        $allPositiveTransactions = $this->repository->getAllPositiveTransactions();
        // Get total items quantity (sum) from all negative transactions
        $totalNegativeTransactionsUnitQuantity = $this->repository->getTotalNegativeTransactionsUnitQuantity();
        // Process all items from negative transactions until they are finished
        $areAllNegativeTransactionsAccounted = $totalNegativeTransactionsUnitQuantity > 0;

        $totalQuantity = $totalCostPrice = 0;

        foreach ($allPositiveTransactions as $positiveTransaction) {

            $currQuantity = $positiveTransaction->getAttribute('quantity');
            $currUnitCostPrice = $positiveTransaction->getAttribute('unit_cost_price');

            // Either account older negative transactions ...
            if ($areAllNegativeTransactionsAccounted) {
                $totalNegativeTransactionsUnitQuantity -= $currQuantity;

                if ($totalNegativeTransactionsUnitQuantity > 0) {
                    // Will account negative transactions further
                    continue;
                }

                // We're done with negative transactions.
                $areAllNegativeTransactionsAccounted = false;

                // Is current transaction still not empty?
                $quantityRemainder = abs($totalNegativeTransactionsUnitQuantity);

                if ($quantity <= $quantityRemainder) {
                    // Finish calculation with current transaction
                    $totalQuantity = $currQuantity;
                    $totalCostPrice = $currUnitCostPrice * $totalQuantity;
                    break;
                }

                // Account current transaction and go to further calculations
                $totalQuantity = $quantityRemainder;
                $totalCostPrice = $quantityRemainder * $currUnitCostPrice;

            } else { // .. or calculate current unit cost price

                $totalQuantity += $currQuantity;

                if ($quantity > $totalQuantity) {
                    $totalCostPrice += $currQuantity * $currUnitCostPrice;
                } else {
                    $quantityRemainder = $totalQuantity - $quantity;
                    $totalQuantity -= $quantityRemainder;
                    $totalCostPrice += ($currQuantity - $quantityRemainder) * $currUnitCostPrice;
                    break;
                }
            }
        }

        return round($totalCostPrice / $totalQuantity, 2);
    }
}