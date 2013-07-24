<?php
/**
 * Description of TableElement
 *
 * @author hugofonseca
 *
 * @method TabCreditosRenegociados valor_renegociado(string $value)     inputs a value in this field
 *
 * @property Selenium2_Element $credito              get this element
 *
 */
class TableElement
{
  protected $tableElement;
  protected $testCase;

  public function __construct($tableElement, $testCase)
  {
    $this->tableElement = $tableElement;
    $this->testCase = $testCase;
  }

  public function tableByRow()
  {
    $fields = null;
    $rows = $this->childRows($this->tableElement);

    for ($i = 0, $nRows = count($rows); $i < $nRows; $i++) {
      $columns = $this->childColumns($rows[$i]);
      for ($c = 0, $nColumns = count($columns); $c < $nColumns; $c++) {
        $value = $columns[$c]->text();

        if ($value == '') {
          // childSpans is very slow
          $spans = $this->childSpans($columns[$c]);
          $value = $this->spansAttribute($spans, 'alt');
          if (empty($value)) {
            $value = $this->spansAttribute($spans, 'data-original-title');
          }
        }

        $fields[$i][$c] = $value;
      }
    }

    return $fields;
  }

  public function tableByColumn()
  {
    $fields = null;
    $headers = $this->tableHeaderNames();
    $rows = $this->childRows($this->tableElement);

    foreach ($rows as $row) {
      $counter = 0;
      $columns = $this->childColumns($row);
      foreach ($columns as $column) {

        $value = $column->text();
        if ($value == '') {
          // childSpans is very slow
          $spans = $this->childSpans($column);
          $value = $this->spansAttribute($spans, 'alt');
        }
        $fields[$headers[$counter]][] = $value;
        $counter++;
      }
    }

    return $fields;
  }

  private function spansAttribute($spans, $attribute)
  {
    $value = null;
    if (count($spans) > 1) {
      foreach ($spans as $span) {
        $value[] = $span->attribute($attribute);
      }
    } elseif (count($spans) == 1) {
      $value = $spans[0]->attribute($attribute);
    }
    return $value;
  }
  public function tableHeaderNames()
  {
    $ths = $this->childHeaders($this->tableElement);
    foreach ($ths as $th) {
      $headers[] = $th->text();
    }

    return $headers;
  }


  protected function childRows($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('tr'));
  }
  protected function childColumns($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('td'));
  }
  protected function childHeaders($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('th'));
  }

  protected function childSpans($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('span'));
  }

  public function childLinks($element)
  {
    return $element->elements($this->testCase->using('css selector')->value('a'));
  }
}
