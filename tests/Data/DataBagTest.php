<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Data\DataBag;
use Tests\TestCase;

final class DataBagTest extends TestCase
{
    public function testConstructor()
    {
        $this->testAll();
    }

    public function testAll()
    {
        $bag = new DataBag([
            'foo' => 'bar',
        ]);

        $this->assertEquals(['foo' => 'bar'], $bag->all());
    }

    public function testGet()
    {
        $bag = new DataBag([
            'foo' => 'bar',
            'null' => null,
        ]);

        $this->assertEquals('bar', $bag->get('foo'));
        $this->assertEquals('default', $bag->get('unknown', 'default'));
        $this->assertNull($bag->get('null', 'default'));
    }

    public function testSet()
    {
        $bag = new DataBag();

        $bag->set('foo', 'bar');
        $this->assertEquals('bar', $bag->get('foo'));

        $bag->set('foo', 'baz');
        $this->assertEquals('baz', $bag->get('foo'));
    }

    public function testHas()
    {
        $bag = new DataBag(['foo' => 'bar']);

        $this->assertTrue($bag->has('foo'));
        $this->assertFalse($bag->has('unknown'));
    }

    public function testJsonSerializable()
    {
        $data = [
            'foo' => 'bar',
        ];

        $bag = new DataBag($data);

        $this->assertEquals(json_encode($data), json_encode($bag));
    }
}
