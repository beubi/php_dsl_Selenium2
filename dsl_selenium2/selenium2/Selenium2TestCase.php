<?php
/**
 * Adds and remove cookies.
 *
 * @method void   add(string $name, string $value)  adds a cookie (?)
 * @method string get(string $name)                 gets a cookie (?)
 * @method void   remove(string $name)              removes a cookie (?)
 * @method void   clear()                           clear all cookies (?)
 * @method void   postCookie(array $data)           (?)
 */
class Selenium2_Session_Cookie
{
  // dummy class - methods auto completion porpuses
}
/**
 * Object representing a browser window.
 *
 * @method array size(array $size = null)         Window size as array('width' => $x, 'height' => $y)
 * @method array position(array $position = null) Window position as array('x' => $x, 'y' => $y)
 * @method array maximize()                       Maximize window
 */
class Selenium2_Window
{
  // dummy class - methods auto completion porpuses
}
/**
 * Conditions for selecting a DOM element.
 *
 * @method Selenium2_ElementCriteria value($name) Retrieves an element's attribute
 */
class Selenium2_ElementCriteria
{
  // dummy class - methods auto completion porpuses
}
/**
 * Object representing a DOM element.
 *
 * @method string attribute($name)                    Retrieves an element's attribute
 * @method void   clear()                             Empties the content of a form element.
 * @method void   click()                             Clicks on element
 * @method string css($propertyName)                  Retrieves the value of a CSS property
 * @method bool   displayed()                         Checks an element's visibility
 * @method bool   enabled()                           Checks a form element's state
 * @method bool   equals(Selenium2_Element $another)  Checks if the two elements are the same on the page
 * @method array  location()                          Retrieves the element's position in the page: keys 'x' and 'y' in the returned array
 * @method string name()                              Retrieves the tag name
 * @method bool   selected()                          Checks the state of an option or other form element
 * @method array  size()                              Retrieves the dimensions of the element: 'width' and 'height' of the returned array
 * @method void   submit()                            Submits a form; can be called on its children
 * @method string value($newValue = NULL)             Get or set value of form elements. If the element already has a value, the set one will be appended to it.
 * @method string text()                              Get content of ordinary elements
 */
class Selenium2_Element
{
  // dummy class - methods auto completion porpuses
}

/**
 * Manages timeouts for the current browser session.
 *
 * @method void implicitWait(int $ms)  Sets timeout when searching for elements
 * @method void asyncScript(int $ms)   Sets timeout for asynchronous scripts executed by Session::executeAsync()
 */
class Selenium2_Session_Timeouts
{
  // dummy class - methods auto completion porpuses
}
 /**
 * Delegate method calls to the driver.
 *
 * @param  string                     $command
 * @param  array                      $arguments
 * @return mixed
 * @method void                       acceptAlert()             Press OK on an alert, or confirms a dialog
 * @method mixed                      alertText($value = NULL)  Gets the alert dialog text, or sets the text for a prompt dialog
 * @method void                       back()
 * @method Selenium2_Element          byCssSelector(string $value)
 * @method Selenium2_Element          byClassName(string $vaue)
 * @method Selenium2_Element          byId(string $value)
 * @method Selenium2_Element          byName(string $value)
 * @method Selenium2_Element          byXPath(string $value)
 * @method Selenium2_Element          byLinkText(string $value)
 * @method void                       click(int $button = 0)                        Click any mouse button (at the coordinates set by the last moveto command).
 * @method void                       clickOnElement($id)
 * @method string                     currentScreenshot()                           BLOB of the image file
 * @method void                       dismissAlert()                                Press Cancel on an alert, or does not confirm a dialog
 * @method Selenium2_Element          element(Selenium2_ElementCriteria $criteria)  Retrieves an element
 * @method array                      elements(Selenium2_ElementCriteria $criteria) Retrieves an array of Element instances
 * @method string                     execute($javaScriptCode)                      Injects arbitrary JavaScript in the page and returns the last
 * @method string                     executeAsync($javaScriptCode)                 Injects arbitrary JavaScript and wait for the callback (last element of arguments) to be called
 * @method void                       forward()
 * @method void                       frame($elementId) Changes the focus to a frame in the page
 * @method void                       moveto(Selenium2_Element $element) Move the mouse by an offset of the specificed element.
 * @method void                       refresh()
 * @method Selenium2_Element_Select   select($element)
 * @method string                     source()          Returns the HTML source of the page
 * @method Selenium2_Session_Timeouts timeouts()
 * @method string title()
 * @method void|string                url($url = NULL)
 * @method Selenium2_ElementCriteria  using($strategy)  Factory Method for Criteria objects
 * @method void                       window($name)     Changes the focus to another window
 * @method string                     windowHandle()    Retrieves the current window handle
 * @method string                     windowHandles()   Retrieves a list of all available window handles
 * @method string                     keys()            Send a sequence of key strokes to the active element.
 * @method void                       closeWindow()     Close the current window.
 *
 * // added by me
 * @method Selenium2_Window           currentWindow()   Current window as object
 * @method Selenium2_Session_Cookie   cookie()          Cookie as object
 *
 */
class Selenium2TestCase extends PHPUnit_Extensions_Selenium2TestCase
{
}

/**
 * @method Selenium2_Element fromElement() Retrieves the current window handle
 * @method string selectedLabel()
 * @method string selectedValue()
 * @method string selectedId()
 * @method array selectedLabels() Retrieves current selected texts
 * @method array selectedValues() Retrieves current selected values
 * @method array selectedIds() Retrieves current selected ids
 * @method void selectOptionByLabel(string $label the text of the option)
 * @method void selectOptionByValue(string $value the value attribute of the option)
 * @method void selectOptionByCriteria(Selenium2_ElementCriteria $localCriteria  conditions for selecting an option)
 * @method array selectOptionValues() Retrieves the select option values
 * @method array selectOptionLabels() Retrieves the select option labels
 * @method void clearSelectedOptions() clears the select
 *
 */
class Selenium2_Element_Select
{
}
