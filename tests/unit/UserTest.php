<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\Customer;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsPrivate()
    {
        $user = new Customer(4, Customer::USER_TYPE_PRIVATE);

        $this->assertTrue($user->isPrivate(), 'User is private so isPrivate() should return TRUE');
        $this->assertFalse($user->isCompany(), 'User is private so isCompany() should return FALSE');
    }

    public function testIsCompany()
    {
        $user = new Customer(4, Customer::USER_TYPE_COMPANY);

        $this->assertFalse($user->isPrivate(), 'User is company so isPrivate() should return FALSE');
        $this->assertTrue($user->isCompany(), 'User is company so isCompany() should return TRUE');
    }
}
