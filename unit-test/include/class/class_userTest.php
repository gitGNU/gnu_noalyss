<?php
define ('USE_ID',999999);
define ('USE_FIRST_NAME','Unit test');
define ('USE_NAME','UNIT');
define ('USE_LOGIN','unit-test');
define ('USE_ACTIVE',1);
define ('USE_PASS','passord');
define ('USE_ADMIN',0);
define ('USE_EMAIL','none@dev.null.eu');
/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-10 at 22:48:23.
 */
class UserTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var User
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // create database connx : 
        $this->cn=new Database();
        // create a user 
        $this->cn->exec_sql('delete from jnt_use_dos where use_id=$1',array(USE_ID));
        $this->cn->exec_sql('delete from ac_users where use_id=$1',array(USE_ID));
        $this->cn->exec_sql('insert into ac_users (use_id,use_first_name,use_name,use_login,use_active,use_pass,use_admin,use_email) values ($1,$2,$3,$4,$5,$6,$7,$8)',
                array(USE_ID,USE_FIRST_NAME,USE_NAME,USE_LOGIN,USE_ACTIVE,USE_PASS,USE_ADMIN,USE_EMAIL));
        
        $this->object=new User($cn,USE_ID);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //drop user
        $this->cn->exec_sql('delete from jnt_use_dos where use_id=$1',array(USE_ID));
        $this->cn->exec_sql('delete from ac_users where use_id = $1',array(USE_ID));
    }

    /**
     * @covers User::load
     * @todo   Implement testLoad().
     */
    public function testLoad()
    {
        
        $this->object->load();
        $this->assertEquals($this->object->id , USE_ID) ;
        $this->assertEquals($this->object->name , USE_NAME) ;
        $this->assertEquals($this->object->first_name , USE_FIRST_NAME );
        $this->assertEquals($this->object->login , USE_LOGIN) ;
        $this->assertEquals($this->object->active , USE_ACTIVE) ;
        $this->assertEquals($this->object->password, USE_PASS) ;
        $this->assertEquals($this->object->admin , USE_ADMIN) ;
        $this->assertEquals($this->object->email , USE_EMAIL) ;
        
        
    }

    /**
     * @covers User::save
     * @todo   Implement testSave().
     */
    public function testSave()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::insert
     * @todo   Implement testInsert().
     */
    public function testInsert()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::Check
     * @todo   Implement testCheck().
     */
    public function testCheck()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_folder_access
     * @todo   Implement testGet_folder_access().
     */
    public function testGet_folder_access()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::set_folder_access
     * @todo   Implement testSet_folder_access().
     */
    public function testSet_folder_access()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_ledger_access
     * @todo   Implement testGet_ledger_access().
     */
    public function testGet_ledger_access()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_ledger
     * @todo   Implement testGet_ledger().
     */
    public function testGet_ledger()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_ledger_sql
     * @todo   Implement testGet_ledger_sql().
     */
    public function testGet_ledger_sql()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::Admin
     * @todo   Implement testAdmin().
     */
    public function testAdmin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::set_periode
     * @todo   Implement testSet_periode().
     */
    public function testSet_periode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_periode
     * @todo   Implement testGet_periode().
     */
    public function testGet_periode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_mini_report
     * @todo   Implement testGet_mini_report().
     */
    public function testGet_mini_report()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::set_mini_report
     * @todo   Implement testSet_mini_report().
     */
    public function testSet_mini_report()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::save_global_preference
     * @todo   Implement testSave_global_preference().
     */
    public function testSave_global_preference()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_preference
     * @todo   Implement testGet_preference().
     */
    public function testGet_preference()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::check_module
     * @todo   Implement testCheck_module().
     */
    public function testCheck_module()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::check_action
     * @todo   Implement testCheck_action().
     */
    public function testCheck_action()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::load_global_pref
     * @todo   Implement testLoad_global_pref().
     */
    public function testLoad_global_pref()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::insert_default_global_pref
     * @todo   Implement testInsert_default_global_pref().
     */
    public function testInsert_default_global_pref()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::update_global_pref
     * @todo   Implement testUpdate_global_pref().
     */
    public function testUpdate_global_pref()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_exercice
     * @todo   Implement testGet_exercice().
     */
    public function testGet_exercice()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_request
     * @todo   Implement testCan_request().
     */
    public function testCan_request()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::check_print
     * @todo   Implement testCheck_print().
     */
    public function testCheck_print()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_print
     * @todo   Implement testCan_print().
     */
    public function testCan_print()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::is_local_admin
     * @todo   Implement testIs_local_admin().
     */
    public function testIs_local_admin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_available_repository
     * @todo   Implement testGet_available_repository().
     */
    public function testGet_available_repository()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_list
     * @todo   Implement testGet_list().
     */
    public function testGet_list()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::check_jrn
     * @todo   Implement testCheck_jrn().
     */
    public function testCheck_jrn()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::check_dossier
     * @todo   Implement testCheck_dossier().
     */
    public function testCheck_dossier()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_limit_current_exercice
     * @todo   Implement testGet_limit_current_exercice().
     */
    public function testGet_limit_current_exercice()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::show_dossier
     * @todo   Implement testShow_dossier().
     */
    public function testShow_dossier()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_available_folder
     * @todo   Implement testGet_available_folder().
     */
    public function testGet_available_folder()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::audit
     * @todo   Implement testAudit().
     */
    public function testAudit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::save_profile
     * @todo   Implement testSave_profile().
     */
    public function testSave_profile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_profile
     * @todo   Implement testGet_profile().
     */
    public function testGet_profile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_writable_profile
     * @todo   Implement testGet_writable_profile().
     */
    public function testGet_writable_profile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::get_readable_profile
     * @todo   Implement testGet_readable_profile().
     */
    public function testGet_readable_profile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_add_action
     * @todo   Implement testCan_add_action().
     */
    public function testCan_add_action()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_write_action
     * @todo   Implement testCan_write_action().
     */
    public function testCan_write_action()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_read_action
     * @todo   Implement testCan_read_action().
     */
    public function testCan_read_action()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_write_repo
     * @todo   Implement testCan_write_repo().
     */
    public function testCan_write_repo()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::can_read_repo
     * @todo   Implement testCan_read_repo().
     */
    public function testCan_read_repo()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::save_password
     * @todo   Implement testSave_password().
     */
    public function testSave_password()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::save_email
     * @todo   Implement testSave_email().
     */
    public function testSave_email()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers User::revoke_access
     * @todo   Implement testRevoke_access().
     */
    public function testRevoke_access()
    {
        $cn_dossier=new Database(DOSSIER);
       User::revoke_access(USE_LOGIN, DOSSIER);
       $this->assertEquals($cn_dossier->get_value('select count(*) from profile_user where user_name =$1 ',array(USE_LOGIN)),0);
    }

    /**
     * @covers User::grant_admin_access
     * @todo   Implement testGrant_admin_access()
     */
    public function testGrant_admin_access()
    {
       $cn_dossier=new Database(TARGET);
       User::grant_admin_access(USE_LOGIN, DOSSIER);
       
       $this->assertEquals($cn_dossier->get_value('select count(*) from profile_user where user_name =$1 ',array(USE_LOGIN)),1);
    }

}
