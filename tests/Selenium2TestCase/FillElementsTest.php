<?php

include_once '/var/www/php_dsl_Selenium2/autoload.php';

/**
 * Test class for Session
 *
 * This test case will test every testable action related to login and logout actions
 *
 * @package    functional
 * @subpackage session
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 *
 */
class FillElementsTest extends ApplicationBaseTestCase
{
  /**
  * This method will test if when the login is sucessfull the user is
  * redirect to the correct page and that logout redirects to login page
  *
  * @access public
  *
  * @return void
   *
   * @group ongoing
  */
  public function testSucessLoginLogout()
  {
    $loginPage = new LoginPage($this);

    $loginPage->username('Hugo')
             ->password('password')
             ->submit();

    $homePage = new HomePage($this);
  }
}
