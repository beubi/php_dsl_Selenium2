<?php

/**
 * Description of WindowForm
 *
 * @author hugofonseca
 *
 */
abstract class AbstractWindowForm extends AbstractContainer
{
  public function closeWindow()
  {
    $this->testCase->frame(null);
    $this->testCase->byXPath("//div[not(contains(@style, 'display: none;'))]/div/a/span[text()='close']")->click();
    $this->testCase->waitForAjax();
  }
}
