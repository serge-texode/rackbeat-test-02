<?php

namespace Tests\Unit;

use App\Inventory\Counter;
use App\Repositories\TransactionRepository;
use Tests\TestCase;

class InventoryTest extends TestCase
{
	/** @var TransactionRepository */
	protected $repository;

	/** @var Counter */
	protected $counter;

	public function setUp() {
		parent::setUp();

		$this->repository = new TransactionRepository();
		$this->counter    = new Counter($this->repository);
	}

	public function test_can_calculate_cost_price() {
		$this->repository->create( 10, 10.0 );

		$this->assertEquals( 10.0, $this->counter->calculateCostPrice( -10 ) );
	}

	public function test_can_calculate_cost_price_with_multiple_purchases_and_sales() {
		$this->repository->create( 10, 10.0 );
		$this->repository->create( 10, 20.0 );
		$this->repository->create( -5, 10.0 );

		$this->assertEquals( 15.0, $this->counter->calculateCostPrice( -10 ) );

		$this->repository->create( -10, 15.0 );

		$this->assertEquals( 20.0, $this->counter->calculateCostPrice( -5 ) );
	}

	public function test_can_calculate_total_stock_count() {
		$this->repository->create( 10, 10.0 );
		$this->repository->create( 20, 8.0 );
		$this->repository->create( -10, null );

		$this->assertEquals( 20, $this->counter->countTotalQuantity( $this->repository->get() ) );

		$this->repository->create( -15, null );

		$this->assertEquals( 5, $this->counter->countTotalQuantity( $this->repository->get() ) );
	}

    public function test_can_calculate_cost_price_to_assert_calculation_algorithm() {

        $this->repository->create( 5, 10.0 );
        $this->repository->create( 10, 20.0);
        $this->repository->create( 0, 100 );

        $this->assertEquals( 16.67, $this->counter->calculateCostPrice( -15 ) );
    }

    public function test_calculate_cost_price_input_positive_value_exception() {

        $this->repository->create( 5, 10.0 );

        $this->expectException(\InvalidArgumentException::class);
        $this->counter->calculateCostPrice( 10 );
    }

    public function test_calculate_cost_price_input_value_out_of_bound_exception() {

        $this->repository->create( 5, 10.0 );

        $this->expectException(\InvalidArgumentException::class);
        $this->counter->calculateCostPrice( -100 );
    }
}
