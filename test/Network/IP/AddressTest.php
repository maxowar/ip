<?php

namespace Network\IP;

use PHPUnit\Framework\TestCase;


class AddressTest extends TestCase
{
    /**
     * @dataProvider provideValidAddresses
     *
     * @param $ip
     */
    public function testAValidAddress($ip)
    {
        $address = new Address($ip);
        $this->assertTrue($address->valid());
    }

    public function provideValidAddresses()
    {
        return [
            ['1.2.3.4'],
            ['0.0.0.0'],
            ['255.255.255.0'],
            ['128.255.0.1']
        ];
    }

    public function provideInvalidAddresses()
    {
        return [
            ['1.2.3'],
            ['0.0.0.0253'],
            ['255.666.255.0'],
            ['128.255.0.1.365']
        ];
    }

    /**
     * @dataProvider provideInvalidAddresses
     *
     * @param $ip
     */
    public function testAnInvalidAddress($ip)
    {
        $address = new Address($ip);
        $this->assertFalse($address->valid());
    }

    public function testDecimalConversion()
    {
        $address = new Address('0.0.0.255');
        $this->assertEquals(255, $address->decimal());

        $address = new Address('0.0.1.255');
        $this->assertEquals(511, $address->decimal());
    }
}
