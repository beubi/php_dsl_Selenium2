This is a work in progress of a DSL Using Selenium2 and phpunit_Selenium

To see details of how to use this, you may check out our tests.

Explanation (?)

Our application has elements. This elements are: labels, tables, headers, inputs, select, textareas...

The elements are group inside a container. This container could be a page, 
which have other containers inside, like menus, tabs, etc.


Every container must define $elements property. This property must be an array similar to:

  protected $elements = array(
    'agencia'       => array('select' , 'byId', 'agencia_id', 'selectOptionByLabel'),
    'note_content'  => array('byId'   , 'note_content'      , 'value'),
  );

If the container is a page than it must have also:

  protected $url = 'test_type_page1';

and

  protected $identifiers = array('header' => 'Test for typing values into form text fields.');

This will be used to check if the current page is actually the page we want.

If, for access the container there must be a click before somewhere you extend it from Tab and put this:

  protected $tab = array('byId', 'Agência');

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/hugofonseca/php_dsl_selenium2/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

