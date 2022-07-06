<?php

class CRM_ctrl_Plugins_INSZ {

  public function convert($insz) {
    return preg_replace("/\D/", "", $insz);
  }

}
