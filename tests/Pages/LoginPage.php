<?php
/**
 * This class is where is defined login page methods, fields, forms, header and everything that is connect with it
 *
 * @package    ubibanking
 * @subpackage login
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 *
 * @method LoginPage header()   get header
 * @method LoginPage username() type in username input
 * @method LoginPage password() type in password input
 *
 * @property Selenium2_Element $username get username element
 * @property Selenium2_Element $password get password element
 * @property Selenium2_Element $submit   get submit element
 * @property Selenium2_Element $header   get header element
 */
class LoginPage extends ProjectPage
{
  protected $url = 'test_type_page1';

  /* objects of the page -> fields, headers, labels, etc.*/
  protected $elements = array(
      'header'     => array('byCssSelector', 'h3'            , 'text'),

      'username'   => array('byName'         , 'username'   , 'value'),
      'password'   => array('byName'         , 'password'   , 'value'),
      'submit'     => array('byId'         , 'submitButton'   , 'submit'),
  );

  protected $identifiers = array('header' => 'Test for typing values into form text fields.');

  /**
  * This method will push submit button and return the HomePage
  *
  * @access public
  *
  * @return HomePage
  */
  public function submit()
  {
    $this->submit->click();
    return new HomePage($this->testCase);
  }
}
