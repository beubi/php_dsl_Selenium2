<?php
/**
 * Custom class for functional tests
 *
 * This class contains methods and defined variables used in tests
 *
 * @package    test
 * @subpackage lib
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 */
abstract class ProjectBase extends ApplicationBaseTestCase
{
  // start url
  public $browserUrl = 'http://localhost/tests/html/test_type_page1.html';
  public $dbType = 'mysql';
  public $browser = 'firefox';
  //public $desiredCapabilities = array();
  public $loadFixtures = false;
  // setUpPage
  public $browserWidth = 1024;
  public $browserHeight = 768;
  public $browserPosition = array(0, 0);
  public $browserMaximized = false;
}
