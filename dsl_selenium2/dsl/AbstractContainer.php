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
abstract class AbstractContainer
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

  private function alowedStrategy($strategy)
  {
    return in_array($strategy, array('byCssSelector', 'byId', 'byName', 'byXPath', 'byClassName', 'byLinkText'));
  }
  private function validateStrategy($strategy)
  {
    // the strategy is not case sensitive but i'll for now, i'll make things this way
    if (! $this->alowedStrategy($strategy)) {
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
   * @return Container $this if function without arguments, the result of that operation will be returned
   */
  final public function __call($name, $arguments)
  {
    $element = $this->$name; // this will call __get probably
    $defaultBehavior = $this->elements[$name][2];
    $this->validateDefaultBehavior($defaultBehavior);

    // take care of dates / calendars
    if ($this->isDate($defaultBehavior)) {
      $this->validateArgs($defaultBehavior, $arguments);

      $this->selectDate($element, $arguments[0]);
      return $this;
    }

    // take care of selects, multi-selects
    if ($this->isSelect($defaultBehavior)) {
      $this->validateArgs($defaultBehavior, $arguments);

      $this->testCase->select($element)->$defaultBehavior($arguments[0]);
      return $this;
    }

    // take care of clicks that must return $this
    if ($this->isClick($defaultBehavior)) {
      $element->click();
      return $this;
    }

    // take care of other elements
    if ($this->hasArgs($arguments)) {
      $element->$defaultBehavior($arguments[0]);
    } else {
      return $element->$defaultBehavior();
    }
    return $this;
  }
  private function validateArgs($defaultBehavior, $arguments)
  {
    if (! $this->hasArgs($arguments)) {
      throw new Exception('~~> '.$defaultBehavior.' without args');
    }
  }
  private function isSelect($defaultBehavior)
  {
    return in_array($defaultBehavior, array('selectOptionByLabel'));
  }
  private function isDate($defaultBehavior)
  {
    return in_array($defaultBehavior, array('date'));
  }
  private function isClick($defaultBehavior)
  {
    return in_array($defaultBehavior, array('click'));
  }
  private function hasArgs($arguments)
  {
    return isset($arguments[0]);
  }

  private function alowedDefaultBehavior($defaultBehavior)
  {
    return in_array($defaultBehavior, array('date', 'selectOptionByLabel', 'attribute', 'clear', 'click', 'css',
      'displayed', 'enabled', 'equals', 'location', 'name', 'selected', 'size', 'submit', 'text', 'value'));
  }
  private function validateDefaultBehavior($defaultBehavior)
  {
    if (! $this->alowedDefaultBehavior($defaultBehavior)) {
      $reflection = new ReflectionClass($this);
      $errMsg = '~~> Invalid DefaultBehavior';
      $errMsg .= "\nContainer:       ".$reflection->getFileName();
      $errMsg .= "\nDefaultBehavior: ".$defaultBehavior;
      throw new Exception($errMsg);
    }
  }

  private function selectDate($dayElement, $stringDate)
  {
    $dayLocId = $dayElement->attribute('id');
    $dayLocator = "//select[@id='".$dayLocId."']";
    $monthLocator = $dayLocator.'/following-sibling::select';
    $yearLocator = $monthLocator.'/following-sibling::select';

    $date = new DateTime($stringDate);
    $day = $date->format('d');
    $month = $date->format('m');
    $year = $date->format('Y');

    $dayElement->value($day);
    $this->testCase->byXPath($monthLocator)->value($month);
    $this->testCase->byXPath($yearLocator)->value($year);
  }
}
