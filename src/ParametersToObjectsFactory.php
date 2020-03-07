<?php

namespace CommissionFee;

class ParametersToObjectsFactory
{
    public function create($inputLine): ParametersToObjects
    {
        return new ParametersToObjects($inputLine);
    }
}
