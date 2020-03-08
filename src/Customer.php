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
    const USER_TYPE_PRIVATE = 'natural';

    /** Company */
    const USER_TYPE_COMPANY = 'legal';

    private int $userId;

    private string $userType;

    public function __construct(int $userId, string $userType)
    {
        $this->userId = $userId;
        $this->userType = $userType;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function isCompany()
    {
        return $this->userType === self::USER_TYPE_COMPANY;
    }

    public function isPrivate()
    {
        return $this->userType === self::USER_TYPE_PRIVATE;
    }
}
