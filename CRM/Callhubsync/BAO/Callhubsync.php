<?php

  class CRM_Callhubsync_BAO_Callhubsync extends CRM_Core_DAO {

    public static function callAPI($endpoint) {
      // create a new cURL resource
      $ch = curl_init();
      # api_key can be found at https://callhub.io/user_detail_change/?action=tabs-1
      $headers = array();
      $headers[] = "Authorization: token " . CALLHUB_APIKEY;
      $headers[] = "Content-Type: application/json";

      // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, $endpoint);
      curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);

      $curl_response = curl_exec($ch); // Send request
      curl_close($ch); // close cURL resource

      $decoded = json_decode($curl_response,true);
      return $decoded;
    }

    public static function saveDnc($params) {
      if (empty($params['results'])) {
        return;
      }
      foreach ($params['results'] as $result) {
        if (!empty($result['phone_number'])) {
          // Phone number is formatted with country code. So, remove the leading digit.
          $phone = ltrim($result['phone_number'], 1);
          // Check database for match on phone number.
          // We do the check on phone_numeric to bypass any formatting that CiviCRM allows on the phone number field.
          $cids = CRM_Core_DAO::executeQuery("SELECT contact_id FROM civicrm_phone WHERE phone_numeric = %1", [1 => [$phone, 'Integer']])->fetchAll();
          if (!empty($cids)) {
            foreach ($cids as $cid) {
              // Match is found, proceed to set do not phone to true.
              civicrm_api3('Contact', 'create', ['id' => $cid['contact_id'], 'do_not_phone' => 1]);
            }
          }
        }
      }
    }

  }