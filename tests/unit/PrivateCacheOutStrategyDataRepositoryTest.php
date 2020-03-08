<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\Customer;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;
use PHPUnit\Framework\TestCase;

class PrivateCacheOutStrategyDataRepositoryTest extends TestCase
{
    public function testAddGetEntry()
    {
        $customerId = 1;
        $amount = 1000.0;

        $repository = new PrivateCacheOutStrategyDataRepository();
        $repository->addEntry(
            new Customer($customerId, Customer::CUSTOMER_TYPE_PRIVATE),
            $amount,
            time()
        );

        $entity = $repository->getDataByCustomerIdAndDate($customerId, time());

        $this->assertSame($entity->getAmount(), $amount);
    }

    public function testAddGetSeveralEntries()
    {
        $customerId = 1;
        $amount = 1200.0;

        $repository = new PrivateCacheOutStrategyDataRepository();
        $repository->addEntry(
            new Customer($customerId, Customer::CUSTOMER_TYPE_PRIVATE),
            1000,
            time()
        );
        $repository->addEntry(
            new Customer($customerId, Customer::CUSTOMER_TYPE_PRIVATE),
            200,
            time()
        );

        $entity = $repository->getDataByCustomerIdAndDate($customerId, time());

        $this->assertSame($entity->getAmount(), $amount);
    }

    public function testAddGetEntryForPastGetRelevantOnly()
    {
        $customerId = 1;
        $amount = 1000.0;

        $repository = new PrivateCacheOutStrategyDataRepository();
        $repository->addEntry(
            new Customer($customerId, Customer::CUSTOMER_TYPE_PRIVATE),
            $amount,
            time()
        );
        $repository->addEntry(
            new Customer($customerId, Customer::CUSTOMER_TYPE_PRIVATE),
            $amount,
            strtotime("first day of previous year")
        );

        $entity = $repository->getDataByCustomerIdAndDate($customerId, time());

        $this->assertSame($entity->getAmount(), $amount);
    }

    public function testAddGetEntryForDifferentUsers()
    {
        $customerId_1 = 1;
        $amount_1 = 1000.0;

        $customerId_2 = 2;
        $amount_2 = 2000.0;

        $repository = new PrivateCacheOutStrategyDataRepository();
        $repository->addEntry(
            new Customer($customerId_1, Customer::CUSTOMER_TYPE_PRIVATE),
            $amount_1,
            time()
        );
        $repository->addEntry(
            new Customer($customerId_2, Customer::CUSTOMER_TYPE_PRIVATE),
            $amount_2,
            time()
        );

        $entity = $repository->getDataByCustomerIdAndDate($customerId_1, time());
        $this->assertSame($entity->getAmount(), $amount_1);

        $entity = $repository->getDataByCustomerIdAndDate($customerId_2, time());
        $this->assertSame($entity->getAmount(), $amount_2);
    }
}
