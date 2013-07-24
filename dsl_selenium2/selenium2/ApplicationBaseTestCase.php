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
  public $dbType = 'mysql';
  protected $fixtures = array();
  public $loadFixtures = true;
  // setUpPage
  public $browserUrl = '';
  public $browserWidth = 1024;
  public $browserHeight = 768;
  public $browserPosition = array(0, 0);
  public $browserMaximized = false;

  /**
   * This method will overwrite _start which executes after every test
   *
   * @access protected
   *
   * @return void
   */
  protected function start()
  {
    // loading of the the fixtures .doctrine
    if ($this->loadFixtures === true) {
      TestsHelper::loadFixtures(
        array($this->getOwnDoctrineFixtureDir(), $this->getCommonFixtureDir()),
        false,
        $this->dbType
      );
    }

    // loading of the fixtures for the data
    if (file_exists(parent::getOwnFixtureDir().DIRECTORY_SEPARATOR.'default.yml')) {
      $this->fixtures = sfYAML::load(parent::getOwnFixtureDir().DIRECTORY_SEPARATOR.'default.yml');
    }
    parent::start();
  }

  /**
   * This method gets executed after session has started.
   * This overrides selenium2 method.
   *
   * @return void
   */
  public function setUpPage()
  {
    $this->url($this->browserUrl);

    $this->configureWindow(
      $this->browserWidth,
      $this->browserHeight,
      array(
        'x' => $this->browserPosition[0],
        'y' => $this->browserPosition[1]
      ),
      $this->browserMaximized);
  }

  /**
   * Returns path for files to be used for upload
   *
   * @return String
   */
  public function getFilesPath()
  {
    // TODO: the browser is executed outside so we dont know where the project is. solution?
    return DIRECTORY_SEPARATOR.implode(
      DIRECTORY_SEPARATOR,
      array('var', 'www', 'geopredial', 'test', 'phpunit', 'fixtures', 'files')
    );
  }

  /**
   * Get the Common fixtures path
   *
   * @access public
   *
   * @return array $paths An array of fixtures paths to be loaded as common.
   */
  public function getCommonFixtureDir()
  {
    return implode(
      DIRECTORY_SEPARATOR,
      array(sfConfig::get('sf_test_dir'), 'phpunit', 'fixtures', 'functionals', 'common')
    );
  }

  /**
   * Temporary function until clients merge
   *
   * @access public
   *
   * @return string $fixturePath Correct fixtures for each client.
   */
  public function getOwnDoctrineFixtureDir()
  {
    if (file_exists(parent::getOwnFixtureDir().DIRECTORY_SEPARATOR.'.doctrine.yml')) {
      return parent::getOwnFixtureDir().DIRECTORY_SEPARATOR.'.doctrine.yml';
    } else {
      return $this->getOwnFixtureDir();
    }
  }

  /**
   * This method changes current browser window properties.
   * this method can only be called during tests
   *
   * @param int    $width    set browser width
   * @param int    $height   set browser heigth
   * @param array  $position set browser monitor position
   * @param bolean $maximize maximize browser
   *
   * @return void
   */
  public function configureWindow($width, $height, $position, $maximize)
  {
    $this->currentWindow()->size(array('width' => $width, 'height' => $height));
    $this->currentWindow()->position($position);
    $maximize ? $this->currentWindow()->maximize() : '';
  }

  /**
   * This method will make the selenium wait for ajax calls to stop
   *
   * @param integer $timeLimit time limit before exception
   *
   * @return void
   */
  public function waitForAjax($timeLimit = 10000000) // 10 seconds
  {
    usleep(200000);
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
  /**
   * Return number of active ajax requests
   *
   * @return integer number of active requests
   */
  private function ajaxActiveRequests()
  {
    return $this->execute(array(
      'script' => 'return $.active',
      'args' => array()
    ));
  }
  /**
   * This method return true if no ajax request is found
   *
   * @return boolean true if no ajax request is found
   */
  private function ajaxStop()
  {
    return $this->execute(array(
      'script' => 'return $.active == 0',
      'args' => array()
    ));
  }

  /**
   * This function will return a string date that can be used with date elements.
   * This function can modify todays according to param $days which can be positive
   * or negative and therefore add or take days from todays date.
   *
   * @param Integer $days days to add or to take from todays date
   *
   * @access public
   *
   * @return string $date Date according to $days
   */
  public function modifiedDate($days = 0)
  {
    return date('d-m-Y', mktime(0, 0, 0, date('m'), date('d') + $days, date('Y')));
  }
}
