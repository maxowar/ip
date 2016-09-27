<?php

namespace Maxowar\IP;

/**
 * Represent an IPv4 address
 *
 * @package Maxowar\IP
 */
class Address
{
    private $address;

    public function __construct($address)
    {
        $this->address = $address;
    }

    /**
     * Is a valid IP?
     */
    public function valid()
    {
        return filter_var($this->address, FILTER_FLAG_IPV4);
    }

    /**
     * Check if Address is contained in a Range
     *
     * @param Range $range
     * @return bool
     */
    public function in(Range $range)
    {
        return $range->contains($this);
    }

    /**
     * Return decimal representation of the address
     *
     * @return float|int
     */
    public function decimal()
    {
        if(PHP_INT_SIZE * 8 == 64) {
            return (float) sprintf('%u', ip2long($this->address));
        }

        return ip2long($this->address);
    }
}
