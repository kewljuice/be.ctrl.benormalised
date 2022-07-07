<?php

class CRM_ctrl_Plugins_Name {


  /**
   * @param $name
   *
   * @return string
   */
  public function firstname($name) {
    // Only convert uppercase version.
    /* if (mb_strtoupper($name, 'utf-8') != $name) {
      return $name;
    } */
    // Skip abbreviation.
    if (strpos($name, ".") !== FALSE) {
      return $name;
    }
    $result = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    return $result;
  }


  /**
   * @param $lastname
   *
   * @return string
   */
  public function lastname($lastname) {
    $handles = [
      'de', // France
      'des',
      'la',
      'den', // Nederland
      'der',
      'ten',
      'ter',
      'van',
      'von', // Germany
      'das',
      'du',
      'en', // Company
      'et',
      'and',
      'und',
      "'s", // skip apostrophe
      "'t"
    ];
    $chunks = explode("-", $lastname);
    $newChunks = [];
    foreach ($chunks as $chunk) {
      // Split Chunks into words
      $words = explode(" ", $chunk);
      $newWords = [];
      foreach ($words as $word) {
        // lower case few matching word
        if (in_array(strtolower($word), $handles)) {
          $word = strtolower($word);
        }
        else {
          // in case name does not contain special handler char, normalize with lower all char and then use ucfirst
          $word = mb_convert_case($word, MB_CASE_TITLE, "UTF-8");
        }
        array_push($newWords, $word);
      }
      $result = join(" ", $newWords);
      array_push($newChunks, $result);
    }
    $name = join("-", $newChunks);
    return $name;
  }
}
