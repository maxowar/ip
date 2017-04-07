<?php

namespace Network\IP;

class Range
{
    /**
     * @var Address
     */
    private $network;

    /**
     * @var Address
     */
    private $broadcast;

    /**
     * @var Address
     */
    private $netmask;

    /**
     * Original range andress definition
     *
     * @var string
     */
    private $range;

    /**
     * The number of available addresses for current range
     *
     * @var integer
     */
    private $wildcard;

    /**
     * Network ranges can be specified as:
     * 1. Wildcard format:     1.2.3.*
     * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * 3. Start-End IP format: 1.2.3.0-1.2.3.255
     *
     * @param string $range
     */
    public function __construct(string $range)
    {
        $this->range = $range;

        // wildcard
        if(strpos($range, '*') !== false) {
            $this->network = new Address(str_replace('*', '0', $range));
            $this->broadcast   = new Address(str_replace('*', '255', $range));

            $this->wildcard = pow(2, substr_count($range, '*') * 8);

        // CIDR
        } elseif ($slashPosition = strpos($range, '/') !== false) {
            // class or netmask

            list($ip, $netmask) = explode('/', $range, 2);

            // fix ip
            $ip_bytes = explode('.', $ip);
            while(count($ip_bytes) < 4) {
                $ip_bytes[] = '0';
            }

            $this->network = new Address(implode('.', $ip_bytes));

            // fix netmask
            if(strpos($netmask, '.')) {
                $netmask = new Address($netmask);
                $netmask = $netmask->decimal();

                $wildcard = ~ $netmask;
            } else {
                $wildcard = pow(2, 32 - $netmask) - 1;
                $netmask = bindec(str_pad('', $netmask, '1') . str_pad('', 32 - $netmask, '0'));
            }

            $this->broadcast = Address::fromDecimal($this->network->decimal() + $wildcard);
            $this->netmask   = $netmask;
            $this->wildcard  = $wildcard;

        // start-end
        } elseif (strpos($range, '-') !== false) {

            list($from, $to) = explode('-', $range, 2);

            $this->network = new Address($from);
            $this->broadcast   = new Address($to);

        // noone of previous
        } else {
            throw new \InvalidArgumentException('Invalid format range');
        }
    }

    public function contains(Address $address)
    {
        return $address->decimal() > $this->network->decimal() &&
            $address->decimal() < $this->broadcast->decimal();
    }

    public function availableAddresses()
    {
        return $this->wildcard;
    }
}
