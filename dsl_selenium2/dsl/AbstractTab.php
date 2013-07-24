<?php

/**
 * Menu abstraction
 *
 * @package    functional
 * @subpackage workflow
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 */
abstract class AbstractTab extends AbstractContainer
{
  protected $tab = null;

  public function __construct(ApplicationBaseTestCase $testCase)
  {
    $strategy = $this->tab[0];
    $locator  = $this->tab[1];
    $testCase->$strategy($locator)->click();
    parent::__construct($testCase);
  }
}
