<?php
/*
 * @codingStandardsIgnoreStart
 *
 * @method void setDesiredCapabilities(array $capabilities) capabilities you may use: @see (https://code.google.com/p/selenium/wiki/DesiredCapabilities)
 *
 * @method void setupSpecificBrowser(array $browserName) $browserName may be: array('browserName' => 'firefox')
 * @method void setSeleniumServerRequestsTimeout(type $timeout timeout for requests default: 60) Set different timeout for requests
 * @method void setPort(integer $port port number default: 4444) Set Port for tests
 * @method void setHost(string $host hostname default: 'localhost') Set Host for tests
 *
 * @method void setBrowser(string $browser browser name) Set browser for tests. $browser may be: 'phantomjs', 'firefox', 'internet explorer', etc)
 * @method void setBrowserUrl(string $url) Set url that selenium will open on start
 *
 * @codingStandardsIgnoreEnd
 */
abstract class BaseTestCase extends PHPUnit_Extensions_Selenium2TestCase
{
  final public function setUp()
  {
    $this->configureDriver();
    $this->startListener();
    $this->start();
  }
  public function tearDown()
  {
    $this->end();
  }
  protected function start()
  {
  }
  protected function end()
  {
  }
  /**
   * This method will start a listener so it printscreen on failure
   *
   * @return void
   */
  final public function startListener()
  {
    $this->directory = sys_get_temp_dir();
    $existing = glob("$this->directory/Tests_Selenium2TestCase_ScreenshotListenerTest__*.png");
    foreach ($existing as $file) {
      unlink($file);
    }
    $this->listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener($this->directory);
  }

  private function configureDriver()
  {
    $this->setBrowserUrl('http://localhost/php_dsl_Selenium2/tests/html/test_type_page1.html');
    $this->setBrowser('chrome');
  }
}
