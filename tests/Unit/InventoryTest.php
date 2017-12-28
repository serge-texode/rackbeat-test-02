<?php

namespace Tests\Unit;

use Tests\TestCase;

class InventoryTest extends TestCase
{
	protected $repository;

	public function setUp() {
		parent::setUp();

		// todo set $this->repository to new App\Repositories\TransactionRepository
	}

	public function test_can_calculate_cost_price() {
		// todo assert that negative Transactions can calculate the unit cost price.
	}

	public function test_can_calculate_total_stock_count() {
		// todo assert that the current stock quantity is 20
		// todo assert that after creating a new transaction with -15 quantity, that current stock quantity is 5
	}
}
