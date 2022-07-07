<?php

class CRM_ctrl_Plugins_INSZ {


  /**
   * @param $insz
   *
   * @return string
   */
  public function clean($insz) {
    return preg_replace("/\D/", "", $insz);
  }

  /**
   * @param $insz
   *
   * @return string
   */
  public function format($insz) {
    if ($insz === NULL || strlen($insz) <> 11) {
      return $insz;
    }
    $year = substr($insz, 0, 2);
    $month = substr($insz, 2, 2);
    $day = substr($insz, 4, 2);
    $counter = substr($insz, 6, 3);
    $check = substr($insz, 9, 2);
    return sprintf('%s.%s.%s-%s.%s', $year, $month, $day, $counter, $check);
  }
}
