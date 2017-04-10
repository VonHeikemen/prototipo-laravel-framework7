<?php

namespace App\Helpers;

class FormatHelper
{
  public static function currency($var)
  {
    $simbol = ($var < 0) ? '-R$ ' : 'R$ ';
    $value = $simbol.number_format(abs($var), 2, ',', '.');
    
    return $value;
  }
}
