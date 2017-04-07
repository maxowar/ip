# Network/IP

Simple classes to manage IPv4 addresses and check networks

# Usage

```php
$address = new Address("192.168.0.2");

$range = new Range("192.168.0.1/24");

if($range->contains($address)) {
    $database->storeIp($address->decimal());
} else {
    throw new \InvalidAddressException("Invalid " . $address . " for the network");
}
```
