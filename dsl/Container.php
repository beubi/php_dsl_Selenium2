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
abstract class Container
{
  protected $elements;
  protected $testCase;

  public function __construct(ApplicationBaseTestCase $testCase)
  {
    $this->testCase = $testCase;
  }
  /**
   * This function will be called whenever you try to get an unknow propertie.
   * This propertie should be defined in elements array of page object.
   * Then it will return that element.
   *
   * @param string $element element name
   *
   * @access public
   *
   * @return Selenium2_Element
   */
  final public function __get($element)
  {
    if (! isset($this->elements[$element])) {
      if (! class_exists($element)) {
        $reflection = new ReflectionClass($this);

        $errMsg = '~~> That element is not defined in the elements of the container.';
        $errMsg .= "\nContainer: ".$reflection->getFileName();
        $errMsg .= "\nElement:   ".$element;
        throw new Exception($errMsg);
      }

      return new $element($this->testCase); // it is a menu or a tab (?)
    }

    $strategy = $this->elements[$element][0]; // may be byCssSelector, byId, byName, byXPath, byClassName, byLinkText
    $locator = $this->elements[$element][1];  // the id || the css locator || the xpath locator || etc

    $this->validateStrategy($strategy);

    if ($strategy === 'select') {
      $strategy = $this->elements[$element][1];
      $locator = $this->elements[$element][2];
    }

    $this->$element = $this->testCase->$strategy($locator);

    if (get_class($this->$element) !== 'PHPUnit_Extensions_Selenium2TestCase_Element') {
      $reflection = new ReflectionClass($this);

      $errMsg = '~~> It was not possible to create a PHPUnit_Extensions_Selenium2TestCase_Element.';
      $errMsg .= "\nContainer: ".$reflection->getFileName();
      $errMsg .= "\nElement:   ".$element;
      throw new Exception($errMsg);
    }

    return $this->$element;
  }

  private function validateStrategy($strategy)
  {
    // the strategy is not case sensitive but i'll for now, i'll make things this way
    if ($strategy !== 'byCssSelector'
      && $strategy !== 'byId'
      && $strategy !== 'byName'
      && $strategy !== 'byXPath'
      && $strategy !== 'byClassName'
      && $strategy !== 'byLinkText'
      && $strategy !== 'select'  // special case
    ) {
      $reflection = new ReflectionClass($this);
      $errMsg = '~~> Invalid strategy';
      $errMsg .= "\nContainer: ".$reflection->getFileName();
      $errMsg .= "\nStrategy:   ".$strategy;
      throw new Exception($errMsg);
    }
  }
  /**
   * This function will be called whenever you try to call an unknow function.
   * This function behavior should be defined in elements array of page object (last array element).
   * Then it will return this page.
   *
   * @param string $name      element name
   * @param string $arguments arguments (optional)
   *
   * @access public
   *
   * @return Page $this if function without arguments, the result of that operation will be returned
   */
  final public function __call($name, $arguments) // call an element as a function (?)
  {
    $element = $this->$name;

    $defaultBehavior = $this->elements[$name][2]; // e.g. click, text, value
    if (! is_string($defaultBehavior)) {
      throw new Exception('bad default behavior: '.var_dump($defaultBehavior));
    }

    if ($defaultBehavior === 'date') {
      if (! isset($arguments[0])) {
        throw new Exception('date without args');
      }
      $dayLocId = $element->attribute('id');
      $dayLocator = "//select[@id='".$dayLocId."']";
      $monthLocator = $dayLocator.'/following-sibling::select';
      $yearLocator = $monthLocator.'/following-sibling::select';
      $date = new DateTime($arguments[0]);
      $day = $date->format('d');
      $month = $date->format('m');
      $year = $date->format('Y');
      $element->value($day);
      $this->testCase->byXPath($monthLocator)->value($month);
      $this->testCase->byXPath($yearLocator)->value($year);
      return $this;
    }

    // fazer o comportamento por defeito
    if (! isset($arguments[0])) {
      return $element->$defaultBehavior();
      //return $this->testCase->$strategy($identifier)->$defaultBehavior();
    } else {
      if ($this->elements[$name][0] === 'select') {
        $defaultBehavior = $this->elements[$name][3];
        $this->testCase->select($element)->$defaultBehavior($arguments[0]);
      } else {
        $element->$defaultBehavior($arguments[0]);
      }//$this->testCase->$strategy($identifier)->$defaultBehavior($arguments[0]);
    }
    return $this;
  }
}
