<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-31 at 20:33:23.
 * this class cannot be tested easily
 */
class Acc_BilanTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Acc_Bilan
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        global $g_connection, $g_parameter;
        $_REQUEST['gDossier']=DOSSIER;
        $g_connection=new Database(DOSSIER);
        $g_parameter=new Own($g_connection);
        $this->object=new Acc_Bilan($g_connection);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Acc_Bilan::display_form
     * @todo   Implement testDisplay_form().
     */
    public function testDisplay_form()
    {
        $r=$this->object->display_form();
        
    }

    /**
     * @covers Acc_Bilan::verify
     * @todo   Implement testVerify().
     */
    public function testVerify()
    {
        $this->object->verify();
    }

    /**
     * @covers Acc_Bilan::get_request_get
     * @todo   Implement testGet_request_get().
     */
    public function testGet_request_get()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::load
     * @todo   Implement testLoad().
     */
    public function testLoad()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::file_open_form
     * @todo   Implement testFile_open_form().
     */
    public function testFile_open_form()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::file_open_template
     * @todo   Implement testFile_open_template().
     */
    public function testFile_open_template()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::compute_formula
     * @todo   Implement testCompute_formula().
     */
    public function testCompute_formula()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::generate_odt
     * @todo   Implement testGenerate_odt().
     */
    public function testGenerate_odt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::generate_plain
     * @todo   Implement testGenerate_plain().
     */
    public function testGenerate_plain()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::generate
     * @todo   Implement testGenerate().
     */
    public function testGenerate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::send
     * @todo   Implement testSend().
     */
    public function testSend()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Acc_Bilan::test_me
     * @todo   Implement testTest_me().
     */
    public function testTest_me()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
