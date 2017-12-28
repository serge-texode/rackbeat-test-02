<?php

namespace Tests\Unit;

use App\Inventory\Counter;
use App\Repositories\TransactionRepository;
use Tests\TestCase;

class InventoryTest extends TestCase
{
	/** @var TransactionRepository */
	protected $repository;

	public function setUp() {
		parent::setUp();

		$this->repository = new TransactionRepository();
	}

	public function test_can_calculate_cost_price() {
		$this->repository->create( 10, 10.0 );
		$saleTransaction = $this->repository->create( -10, null );

		$this->assertEquals( 10.0, $saleTransaction->unit_cost_price );
	}

	public function test_can_calculate_total_stock_count() {
		$inventoryCounter = new Counter();
		$this->repository->seed();

		$this->assertEquals( 20, $inventoryCounter->countTotalQuantity( $this->repository->get() ) );

		$this->repository->create(-15, null);

		$this->assertEquals( 5, $inventoryCounter->countTotalQuantity( $this->repository->get() ) );
	}
}
