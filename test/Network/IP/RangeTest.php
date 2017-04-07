<?php

namespace Network\IP;


use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    /**
     * @dataProvider validIps
     */
    public function testIpInsideARange($ip)
    {
        $range = new Range('192.168.0.1/255.255.255.0');
        $this->assertTrue($range->contains(new Address($ip)));
    }

    public function validIps()
    {
        return [
            ['192.168.0.2'],
            ['192.168.0.254'],
            ['192.168.0.255'],
        ];
    }

    /**
     * @dataProvider outOfRangeIps
     */
    public function testIpOutOfRange($ip)
    {
        $range = new Range('192.168.0.1/255.255.255.0');
        $this->assertFalse($range->contains(new Address($ip)));
    }

    public function outOfRangeIps()
    {
        return [
            ['10.10.10.255']
        ];
    }
}
