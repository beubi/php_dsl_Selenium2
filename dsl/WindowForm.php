<?php

/**
 * Description of WindowForm
 *
 * @author hugofonseca
 *
 */
abstract class WindowForm extends Container
{
  public function closeWindow()
  {
    throw new Exception('Not implemented');
  }
}
