<?php

/**
 * Menu abstraction
 *
 * @package    functional
 * @subpackage workflow
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 */
abstract class Tab extends Container
{
  protected $tab = null;

  public function __construct(ApplicationBaseTestCase $testCase)
  {
    $strategy = $this->tab[0];
    $locator  = $this->tab[1];
    $testCase->$strategy($locator)->click();
    parent::__construct($testCase);
  }

  protected function childRows($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('tr'));
  }
  protected function childColumns($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('td'));
  }
  protected function childHeaders($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('th'));
  }

  protected function childSpans($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('span'));
  }
}
