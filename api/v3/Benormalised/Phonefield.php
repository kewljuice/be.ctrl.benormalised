<?php

use Civi\Api4\Phone;
use CRM_ctrl_Benormalised_ExtensionUtil as E;

/**
 * Benormalised.Phonefield API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_benormalised_Phonefield_spec(&$spec) {
  $spec['Field']['api.required'] = 1;
  $spec['Plugin']['api.required'] = 1;
  $spec['Function']['api.required'] = 1;
  $spec['Limit']['api.required'] = 1;
}

/**
 * Benormalised.Phonefield API
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
function civicrm_api3_benormalised_Phonefield($params) {

  if (array_key_exists('Field', $params)
    && array_key_exists('Plugin', $params)
    && array_key_exists('Function', $params)
    && array_key_exists('Limit', $params)) {

    $fields = Phone::get(FALSE)
      ->addSelect($params['Field'])
      ->addWhere($params['Field'], 'IS NOT EMPTY')
      ->execute();

    $normalise = [];
    foreach ($fields as $field) {
      if (isset($field[$params['Field']])) {
        if (class_exists($params['Plugin'])) {
          $plugin = new $params['Plugin'];
          $function = (string) $params['Function'];
          if (method_exists($plugin, $function)) {
            $A = $field[$params['Field']];
            $B = $plugin->$function($A);
            if ($A != $B) {
              $item['id'] = $field['id'];
              $item['A'] = $A;
              $item['B'] = $B;
              $normalise[] = $item;
              // @todo remove logging.
              Drupal::logger('benormalised')->notice(print_r($item, TRUE));
            }
          }
          else {
            throw new API_Exception('Incorrect function');
          }
        }
        else {
          throw new API_Exception('Incorrect plugin');
        }
      }
    }

    if (is_numeric($params['Limit']) && $params['Limit'] != -1) {
      $limit = $params['Limit'];
      if ($limit == 0) {
        $limit = count($normalise);
      }
      for ($i = 0; $i < $limit; $i++) {
        if (isset($normalise[$i])) {
          Phone::update(FALSE)
            ->addValue($params['Field'], $normalise[$i]['B'])
            ->addWhere('id', '=', $normalise[$i]['id'])
            ->execute();
        }
        else {
          break;
        }
      }
    }

    $returnValues = [
      'Field' => $params['Field'],
      'Plugin' => $params['Plugin'],
      'Function' => $params['Function'],
      'Limit' => $params['Limit'],
      'values' => count($normalise) . "/" . $fields->count(),
    ];
    // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($returnValues, $params, 'Benormalised', 'Phonefield');
  }
  else {
    throw new API_Exception('Incorrect required parameters');
  }
}
