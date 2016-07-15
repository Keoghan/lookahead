<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'Dummy.php';

class BasicTest extends \PHPUnit\Framework\TestCase
{
    protected $dummy;

    protected function setUp()
    {
        parent::setUp();
        $this->dummy = new Dummy;
    }

    public function testNotChained()
    {
        $this->assertEquals($this->dummy->something(), 'Not Chained');
    }


    public function testChained()
    {
        $this->assertEquals($this->dummy->something()->somethingElse(), 'Chained');
    }

    public function testMultiLine()
    {
        $result = $this->dummy
            ->something()
            ->somethingElse();

        $this->assertEquals($result, 'Chained');
    }
}
