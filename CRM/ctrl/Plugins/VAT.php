<?php

class CRM_ctrl_Plugins_VAT {

  /**
   * @param $vat
   *
   * @return string
   */
  public function clean($vat) {
    $clean = preg_replace("/\D/", "", $vat);
    if ($clean === NULL || strlen($clean) <> 10) {
      return $vat;
    }
    return $clean;
  }
}
