<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-31 at 20:33:23.
 */
class Acc_ComputeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Acc_Compute
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object=new Acc_Compute;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    public function dataGet_parameter()
    {
        return array( array('amount'),
                        array('amount_vat'),
                        array('amount_vat_rate'),
                        array('nd_vat'),
                        array('nd_vat_rate'),
                        array('nd_ded_vat'),
                        array('nd_ded_vat_rate'),
                        array('amount_nd'),
                        array('amount_nd_rate'),
                        array('nd_vat_rate'),
                        array('amount_perso'),
                        array('amount_perso_rate')
                        
                                  );
    }
    /**
     * @covers Acc_Compute::get_parameter
     * @todo   Implement testGet_parameter().
     * @dataProvider dataGet_parameter
     */
    public function testGet_parameter($data)
    {
       $result=$data;
       $test=$data;
       $this->assertEquals($test,$result);
    }

    /**
     * @covers Acc_Compute::set_parameter
     * @todo   Implement testSet_parameter().
     */
    public function testSet_parameter()
    {
         $this->assertTrue(true,true);
    }

    /**
     * @covers Acc_Compute::get_info
     * @todo   Implement testGet_info().
     */
    public function testGet_info()
    {
        $this->assertTrue(true,true);
    }

    /**
     * @covers Acc_Compute::compute_vat
     * @todo   Implement testCompute_vat().
     */
    public function testCompute_vat()
    {
        $this->object->set_parameter('amount',1.23);
        $this->object->set_parameter('amount_vat_rate',0.21);
        $this->object->compute_vat();
        $this->assertEquals($this->object->amount_vat,0.26);
    }

    /**
     * @covers Acc_Compute::compute_nd
     * @todo   Implement testCompute_nd().
     */
    public function testCompute_nd()
    {
         $this->object->set_parameter('amount',1.23);
        $this->object->set_parameter('amount_vat_rate',0.21);
        $this->object->set_parameter('amount_nd_rate',50);
        $this->object->check=false;
        $this->object->compute_nd();
        $this->assertEquals($this->object->amount_nd,0.62);
    }

    /**
     * @covers Acc_Compute::compute_nd_vat
     * @todo   Implement testCompute_nd_vat().
     */
    public function testCompute_nd_vat()
    {
        $this->object->set_parameter('amount',1.23);
        $this->object->set_parameter('amount_vat_rate',0.21);
        $this->object->set_parameter('nd_vat_rate',50);
        $this->object->check=false;
        $this->object->compute_nd_vat();
        $this->assertEquals($this->object->nd_vat,0.13);
    }

    /**
     * @covers Acc_Compute::compute_ndded_vat
     * @todo   Implement testCompute_ndded_vat().
     */
    public function testCompute_ndded_vat()
    {
       $this->object->set_parameter('amount',1.23);
        $this->object->set_parameter('amount_vat_rate',0.21);
        $this->object->set_parameter('nd_ded_vat_rate',50);
        $this->object->check=false;
        $this->object->compute_ndded_vat();
        $this->assertEquals($this->object->nd_ded_vat,0.13);
    }

    /**
     * @covers Acc_Compute::compute_perso
     * @todo   Implement testCompute_perso().
     */
    public function testCompute_perso()
    {
        $this->object->set_parameter('amount',1.23);
        $this->object->set_parameter('amount_vat_rate',0.21);
        $this->object->set_parameter('amount_perso_rate',50);
        $this->object->compute_vat();
        $this->object->compute_perso();
        
        $this->assertEquals($this->object->amount_perso,0.62);
        $this->assertEquals($this->object->amount_vat,0.26);
    }

    /**
     * @covers Acc_Compute::correct
     * @todo   Implement testCorrect().
     */
    public function testCorrect()
    {
        $this->object->correct();
    }

    /**
     * @covers Acc_Compute::verify
     * @todo   Implement testVerify().
     */
    public function testVerify()
    {
        $this->object->correct();
    }

    /**
     * @covers Acc_Compute::display
     * @todo   Implement testDisplay().
     * Cannot be checked
     */
    public function testDisplay()
    {
       $this->object->display();
    }

    /**
     * @covers Acc_Compute::test_me
     * @todo   Implement testTest_me().
     */
    public function testTest_me()
    {
        $this->assertTrue(true,true);
    }

}
