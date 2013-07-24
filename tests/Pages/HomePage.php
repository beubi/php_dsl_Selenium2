<?php
/**
 * This class is where is defined login page methods, fields, forms, header and everything that is connect with it
 *
 * @package    ubibanking
 * @subpackage login
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 *
 * @method LoginPage header()   get header
 *
 * @property Selenium2_Element $header   get header element
 */
class HomePage extends AbstractPage
{
  protected $url = 'test_type_page2';

  protected $elements = array(
      'header'     => array('byCssSelector', 'h2'            , 'text'),
  );

  protected $identifiers = array('header' => 'Welcome');
}
