<?php
/**
 * DSL Page abstraction
 *
 * This component represents a page abstration for Tests
 *
 * @package    test
 * @subpackage dsl
 * @author     Ubiprism Lda. / be.ubi <contact@beubi.com>
 *
 * @property ApplicationBaseTestCase $testCase Test Instance
 */
abstract class Page extends Container
{
  protected $url = null;
  protected $identifiers = null;

  public function __construct(ApplicationBaseTestCase $testCase)
  {
    parent::__construct($testCase);
    $this->validatePage();
  }

  private function validatePage()
  {
    $this->validateUrl();

    $pageIds = $this->identifiers;
    if (is_null($pageIds)) {
      $reflection = new ReflectionClass($this);
      throw new Exception('~~> At least one identifier for the page must be setted! Page: '.$reflection->getFileName());
    }

    foreach ($pageIds as $pageId => $expectedValue) {
      $currentValue = $this->$pageId();
      if (strpos($currentValue, $expectedValue) === false) {
        throw new Exception("~~> 1Page failed to be validated!
          Failed asserting that expected '".$expectedValue."' is equal to current '".$currentValue."'."
        );
      }
    }
  }

  private function validateUrl()
  {
    $expectedUrl = $this->url;

    if (is_null($expectedUrl)) {
      $reflection = new ReflectionClass($this);
      throw new Exception('~~> An url for the page must be setted! Page: '.$reflection->getFileName());
    }

    $currentUrl = $this->testCase->url();

    if (strpos($currentUrl, $expectedUrl) === false) {

      throw new Exception("~~> The URL is not the expected one!
        Failed asserting that '".$currentUrl."' contains '".$expectedUrl."'."
      );
    }
  }
  /**
   * Every time a page is refreshed the element setted need to be unsetted so magic __set and __call works.
   *
   * @access public
   *
   * @return void
   */
  protected function resetPage()
  {
    foreach (get_object_vars($this) as $propName => $prop) {
      if ($propName === 'elements'
        || $propName === 'testCase'
        || $propName === 'url'
        || $propName === 'identifiers'
      ) {
        continue;
      }
      unset($this->$propName);
    }
  }
}
