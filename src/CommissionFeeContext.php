<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.54
 */

namespace CommissionFee;

use CommissionFee\Operation\Operation;

class CommissionFeeContext
{
    private int $dateTimeStamp;
    private User $user;
    private Operation $operation;
    private UserDataRepository $userData;

    /**
     * CommissionFeeCalculator constructor dependent on context chooses correct strategy to
     * calculate commission fee.
     *
     * @param int       $dateTimeStamp
     * @param User      $user
     * @param Operation $operation
     */
    public function __construct(
        int $dateTimeStamp,
        User $user,
        Operation $operations,
        UserDataRepository $userData
    ) {
        $this->dateTimeStamp = $dateTimeStamp;
        $this->user = $user;
        $this->operation = $operations;
        $this->userData = $userData;
    }

    public function getDateTimeStamp(): int
    {
        return $this->dateTimeStamp;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function getUserData(): UserDataRepository
    {
        return $this->userData;
    }
}
