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
abstract class ApplicationBaseTestCase extends BaseTestCase
{
  // this method can only be called during tests
  public function configureWindow(
      $size = array('width' => 1024, 'height' => 768),
      $position = array('x' => 0, 'y' => 0),
      $maximize = false
  ) {
    $this->currentWindow()->size($size);
    $this->currentWindow()->position($position);
    $maximize ? $this->currentWindow()->maximize() : '';
  }

  public function waitForAjax($timeLimit = 10000000) // 10 seconds
  {
    $timeExp = 0;
    $timeRem = $timeLimit;
    while (true) {
      echo $this->ajaxActiveRequests().' = ';

      $timeExp = (($timeLimit - $timeRem) / 10000);
      if ($timeRem <= 0 || $this->ajaxStop() == true) {
        echo $timeExp." => OK\n";
        break;
      }
      usleep(50000); // half of a half a second
      $timeRem = $timeRem - 50000;
    }
  }
  private function ajaxActiveRequests()
  {
    return $this->execute(array(
      'script' => 'return $.active',
      'args' => array()
    ));
  }
  private function ajaxStop()
  {
    return $this->execute(array(
      'script' => 'return $.active == 0',
      'args' => array()
    ));
  }
}
