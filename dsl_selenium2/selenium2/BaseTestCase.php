<?php
/**
 * Thsi class extends from Selenium2TestCase and will load every necessary thing so that test gets executed
 *
 * @property-write void $browser May be: phantomjs,internet explorer,chrome - (although driver my be needed) def:firefox
 * @property-write void $browserUrl Url that'll be open on start. for ie and phantomjs we cant use cred. throught url
 * @property-write void $desiredCapabilities @see (https://code.google.com/p/selenium/wiki/DesiredCapabilities)
 * @property-write void $seleniumServerRequestsTimeout Set different timeout for requests. default: 60
 *
 * @method void setupSpecificBrowser(array $browserName) $browserName may be: array('browserName' => 'firefox')
 * @method void setPort(integer $port port number default: 4444) Set Port for tests
 * @method void setHost(string $host hostname default: 'localhost') Set Host for tests
 *
 * @method void listener() use "$this->listener->addError($this, new Exception(), null);" to force printscreen
 *
 * @package selenium2
 * @author  Hugo Fonseca <hugo.fonseca@beubi.com>
 *
 */
abstract class BaseTestCase extends Selenium2TestCase
{
  // for windows and phantomjs we cant use credentials throught url
  // concilium eg: http://beubi:trafil0concilium@lh.concilium.com/frontend_dev.php/
  public $browserUrl = '';
  public $browser = 'firefox';
  public $desiredCapabilities = array();
  public $seleniumServerRequestsTimeout = 80;

  protected $backupSfConfig = array();
  /**
   * This method is runned before every test. Belongs to phpunit.
   * This method will copy symfony settings to $backupSfConfig.
   *
   * @return void
   */
  final public function setUp()
  {
    $start = microtime(true);

    $this->backupSfConfig();
    $this->configureDriver();
    $this->startListener();
    $this->start();

    $res = round(microtime(true) - $start, 3);
    error_log('Time to start: '.$res.' seconds');
  }
  /**
   * This method is runned after every test. Belongs to phpunit
   * This method will copy $backupSfConfig to symfony settings.
   *
   * @return void
   */
  public function tearDown()
  {
    $this->restoreSfConfig();
    $this->end();
  }
  /**
   * This method is runned before every test. It allows to run things before test but after browser has started
   *
   * @return void
   */
  protected function start()
  {
  }
  /**
   * This method is runned after every test. It allows to run things after test but after browser has closed
   *
   * @return void
   */
  protected function end()
  {
  }
  /**
   * This method will start a listener so it printscreen on failure. (may not be working at 100%)
   * To request a print do: $this->listener->addError($this, new Exception(), null);
   *
   * @return void
   */
  final public function startListener()
  {
    $this->directory = sys_get_temp_dir();
    $existing = glob($this->directory.'/Tests_Selenium2TestCase_ScreenshotListenerTest__*.png');
    foreach ($existing as $file) {
      unlink($file);
    }
    $this->listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener($this->directory);
  }
  /**
   * Method that copy symfony settings to $backupSfConfig
   *
   * @return void
   */
  private function backupSfConfig()
  {
    $this->backupSfConfig = sfConfig::getAll();
  }
  /**
   * Method that load $backupSfConfig to symfony settings
   *
   * @return void
   */
  private function restoreSfConfig()
  {
    sfConfig::clear();
    sfConfig::add($this->backupSfConfig);
  }

  /**
   * This method will load the configurations for the phpunit_selenium2
   *
   * @return void
   */
  private function configureDriver()
  {
    $this->setBrowserUrl($this->browserUrl);
    $this->setBrowser($this->browser);
    $this->setSeleniumServerRequestsTimeout($this->seleniumServerRequestsTimeout);
    //$this->setHost('localhost');
    //$this->setPort(4444);
    $this->setDesiredCapabilities($this->desiredCapabilities);
  }

  // ==============================
  // ==============================          Fixtures        NOT implemented yet       =======================
  // ==============================

  public function getPackageFixtureDir()
  {
    $reflection = new ReflectionClass($this);
    $path = dirname($reflection->getFileName());

    $replace = 'fixtures'.DIRECTORY_SEPARATOR;
    $search = 'phpunit'.DIRECTORY_SEPARATOR;

    return substr_replace($path, $replace, strpos($path, $search) + 8, 0);
  }
  public function getOwnFixtureDir()
  {
    $reflection = new ReflectionClass($this);
    $path = str_replace('.php', '', $reflection->getFileName());

    $replace = 'fixtures'.DIRECTORY_SEPARATOR;
    $search = 'phpunit'.DIRECTORY_SEPARATOR;

    return substr_replace($path, $replace, strpos($path, $search) + 8, 0);
  }
  public function getCommonFixtureDir()
  {
    $path = array(sfConfig::get('sf_test_dir'), 'phpunit', 'fixtures', 'common');

    return implode(DIRECTORY_SEPARATOR, $path);
  }
  public function getSymfonyFixtureDir()
  {
    $path = array(sfConfig::get('sf_data_dir'), 'fixtures');

    return implode(DIRECTORY_SEPARATOR, $path);
  }
}
