<?php

use CRM_ctrl_Benormalised_ExtensionUtil as E;

/**
 * Benormalized.Contactfield API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_benormalised_Contactfield_spec(&$spec) {
  $spec['Field']['api.required'] = 1;
  $spec['Plugin']['api.required'] = 1;
}

/**
 * Benormalized.Contactfield API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 * @see civicrm_api3_create_success
 *
 */
function civicrm_api3_benormalised_Contactfield($params) {

  if (array_key_exists('Field', $params) && array_key_exists('Plugin', $params)) {

    $contactFields = \Civi\Api4\Contact::get()
      ->addSelect($params['Field'])
      ->addWhere($params['Field'], 'IS NOT EMPTY')
      ->execute();

    $normalise = [];
    foreach ($contactFields as $contactField) {
      if (isset($contactField[$params['Field']])) {
        $plugin = new $params['Plugin'];
        $A = $contactField[$params['Field']];
        $B = $plugin->convert($A);
        // @todo remove logging.
        if($A != $B) {
          $normalise[] = $contactField;
          \Drupal::logger('normalise:')->notice($A . "==" . $B);
        }
      }
    }

    $returnValues = [
      'Field' => $params['Field'],
      'Plugin' => $params['Plugin'],
      'values' => count($normalise) . "/" . $contactFields->count(),
    ];
    // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($returnValues, $params, 'Benormalised', 'Customfield');

  }
  else {
    throw new API_Exception(/*error_message*/ 'Incorrect required parameters', /*error_code*/ 'params_incorrect');
  }
}
