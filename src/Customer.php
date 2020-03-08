<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 21.02
 */

namespace CommissionFee;


class Customer
{
    /** Private person */
    const CUSTOMER_TYPE_PRIVATE = 'natural';

    /** Company */
    const CUSTOMER_TYPE_COMPANY = 'legal';

    private int $customerId;

    private string $customerType;

    public function __construct(int $customerId, string $customerType)
    {
        $this->customerId = $customerId;
        $this->customerType = $customerType;
    }

    public function getId()
    {
        return $this->customerId;
    }

    public function isCompany()
    {
        return $this->customerType === self::CUSTOMER_TYPE_COMPANY;
    }

    public function isPrivate()
    {
        return $this->customerType === self::CUSTOMER_TYPE_PRIVATE;
    }
}
