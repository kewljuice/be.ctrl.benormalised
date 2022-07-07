<?php

class CRM_ctrl_Plugins_Text {

  /**
   * @param $text
   *
   * @return string
   */
  public function uppercasefirst($text) {
    $result = implode('-', array_map('ucfirst', explode('-', strtolower($text))));
    $result = implode('(', array_map('ucfirst', explode('(', $result)));
    return $result;
  }

  /**
   * @param $text
   *
   * @return string
   */
  public function lowercase($text) {
    $result = mb_strtolower($text);
    return $result;
  }

  /**
   * @param $text
   *
   * @return string
   */
  public function uppercase($text) {
    $result = mb_strtoupper($text);
    return $result;
  }
}
