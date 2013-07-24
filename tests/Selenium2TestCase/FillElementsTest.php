<?php

include_once '/home/hugofonseca/php_dsl_Selenium2/autoload.php';

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
class FillElementsTest extends ProjectBase
{
  /**
  * This method will test if when the login is sucessfull the user is
  * redirect to the correct page and that logout redirects to login page
  *
  * @access public
  *
  * @return void
  */
  public function testSucessLoginLogout()
  {
    $loginPage = new LoginPage($this);

    $loginPage->username('User')
              ->password('Password')
              ->submit();
  }
}
