<?php
/**
 * Helper class with utility methods for tests
 *
 * @package    test
 * @subpackage subpackage
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 */
class TestsHelper
{
  /**
   * Load fixtures from an array of fixtures path
   *
   * @param array/string $fixturesPath An string path or an array of paths for fixtures.
   * @param boolean      $append       Append to the current fixtures, true to append, otherwise to empty tables
   * before load.
   * @param string       $dbType       database type
   *
   * @access protected
   *
   * @static
   * @return void.
   */
  public static function loadFixtures($fixturesPath, $append = false, $dbType = 'mysql')
  {
    switch ($dbType) {
      case 'mysql':
        TestsHelper::disableConstraints();
        //load data from temp dir
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, false);
        Doctrine_Core::loadData(TestsHelper::getFixturesData($fixturesPath), $append);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
        TestsHelper::enableConstraints();
        break;
      case 'informix':
        // drop and create tables
        $informix = new Doctrine_Export_Informix();
        $informix->dropDatabase('');
        Doctrine_Core::createTablesFromModels(sfConfig::get('sf_lib_dir').'/model/doctrine');
        //load data from temp dir
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, false);
        Doctrine_Core::loadData(TestsHelper::getFixturesData($fixturesPath), $append);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
        break;
      default:
        throw new Exception('Type of database unknow!');
    }
  }

  /**
   * d
   *
   * @param type $filePath d
   *
   * @static
   * @return array
   */
  private static function getFixturesData($filePath = '')
  {
    $dataFixturesPath = array();
    if (!is_array($filePath)) {
      $dataFixturesPath = array($filePath);
    } elseif (!is_null($filePath) && $filePath != '') {
      $dataFixturesPath[] = $filePath;
    }
    $paths = array();
    array_walk_recursive($dataFixturesPath, function ($value, $key) use (& $paths) {
        $paths[] = $value;
    });

    //create a temporary random dir
    $testsTempPath = sys_get_temp_dir().'/test_fixtures_'.rand(11111, 99999);
    is_dir($testsTempPath) ? TestsHelper::rrmdir($testsTempPath) : '';
    mkdir($testsTempPath);

    foreach ($paths as $path) {
      $filesToCopy = TestsHelper::getFixturesFiles($path);
      TestsHelper::copyFiles($filesToCopy, $testsTempPath);
    }

    return $testsTempPath;
  }

  /**
   * sd
   *
   * @param array  $filesToCopy s
   * @param string $path        s
   *
   * @static
   * @return array
   */
  private static function copyFiles($filesToCopy, $path)
  {
    foreach ($filesToCopy as $file) {
      error_log($file);
      copy($file, $path.DIRECTORY_SEPARATOR.basename($file));
    }
  }

  /**
   * sd
   *
   * @param type $path s
   *
   * @static
   * @return array
   */
  private static function getFixturesFiles($path)
  {
    $finder = sfFinder::type('file')->name('*.doctrine.yml')->sort_by_name()->follow_link();
    $filesToCopy = array();

    if (is_dir($path)) {
      foreach ($finder->in($path) as $file) {
        $filesToCopy[] = $file;
      }
    } elseif (is_file($path)) {
      $filesToCopy[] = $path;
    }

    return $filesToCopy;
  }

  /**
   * Set constraints in for all current connections
   *
   * @param boolean $value Boolean flag to set and unset the database contraints.
   *
   * @access public
   * @static
   *
   * @return void.
   */
  public static function setConstraints($value)
  {
    foreach (Doctrine_Manager::getInstance()->getConnections() as $conn) {
      /*
       * @var Doctrine_Connection $conn Description
       */
      if ($value == false) {
        $conn->exec('SET FOREIGN_KEY_CHECKS = 0;');
      } else {
        $conn->exec('SET FOREIGN_KEY_CHECKS = 1;');
      }
    }
  }

  /**
   * Disable database constraints in all active connections
   *
   * @access private
   * @static
   *
   * @return void.
   */
  private static function disableConstraints()
  {
    TestsHelper::setConstraints(false);
  }

    /**
   * Enable database constraints in all active connections
   *
   * @access private
   * @static
   *
   * @return void.
   */
  private static function enableConstraints()
  {
     TestsHelper::setConstraints(true);
  }

  /**
   * Delete files in given path (and subdirs)
   *
   * @param string $path Path to the files to delete (should end with slash or backslash)
   *
   * @static
   * @return integer        Returns how many files that were deleted
   */
  public static function deleteAllFilesAndFolders($path)
  {
    $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
      if ($file->isDir()) {
        @rmdir($file->getRealPath());
      } else {
        @unlink($file->getRealPath());
      }
    }
  }
  /**
   * This function copies all the files present in the $src directory to $dst
   *
   * @param string $src Path to source file.
   * @param string $dst Path to destination file.
   *
   * @static
   * @return nothing
   */
  public static function rcopy($src, $dst)
  {
    $filesystem = new sfFilesystem();
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if (is_dir($src.'/'.$file)) {
          TestsHelper::rcopy($src.'/'.$file, $dst.'/'.$file);
        } else {
          $filesystem->copy($src.'/'.$file, $dst.'/'.$file, array('override' => true));
        }
      }
    }
    closedir($dir);
  }

  /**
   * This method will reset all database, table id's etc...
   *
   * This call will drop all tabels, will create, and will load the current available schema.sql into database
   * This won't generate models/forms/filters
   *
   * @param string $appname Application name.
   *
   * @access public
   *
   * @return void.
   */
  public function cleanDatabase($appname)
  {
    //Doctrine::createTablesFromModels(sfConfig::get('sf_lib_dir'));
    $configuration = ProjectConfiguration::getApplicationConfiguration($appname, 'test', true);
    $doctrine = new sfDoctrineDropDbTask($configuration->getEventDispatcher(), new sfAnsiColorFormatter());
    $doctrine->run(array(), array('--no-confirmation','--env=test'));

    $doctrine = new sfDoctrineBuildDbTask($configuration->getEventDispatcher(), new sfAnsiColorFormatter());
    $doctrine->run(array(), array('--env=test'));

    $doctrine = new sfDoctrineInsertSqlTask($configuration->getEventDispatcher(), new sfAnsiColorFormatter());
    $doctrine->run(array(), array('--env=test'));
  }

  /**
   * Remove dir and all of his content
   *
   * @param string $dir Dir path to remove.
   *
   * @access public
   * @static
   *
   * @return void.
   */
  public static function rrmdir($dir)
  {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != '.' && $object != '..') {
          if (filetype($dir.'/'.$object) == 'dir') {
            rrmdir($dir.'/'.$object);
          } else {
            unlink($dir.'/'.$object);
          }
        }
      }
      reset($objects);
      rmdir($dir);
    }
  }
}
