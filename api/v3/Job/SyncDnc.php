<?php
use CRM_Callhubsync_BAO_Callhubsync as E;

/**
 * Job.SyncDnc API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_sync_dnc($params) {
  $endpoint = "https://api.callhub.io/v1/dnc_contacts/";
  $dncContacts = E::callAPI($endpoint);
  E::saveDnc($dncContacts);
  $nextPage = $dncContacts['next'];
  while (!empty($nextPage)) {
    $dncContact = E::callAPI($nextPage);
    E::saveDnc($dncContact);
    $nextPage = $dncContact['next'];
  }
  if (!empty($dncContacts['count'])) {
    $message = ts('Synced %1 contacts on all do not call lists from CallHub.', [1 => $dncContacts['count']]);
    return civicrm_api3_create_success($message, $params, 'Job', 'sync_dnc');
  }
  else {
    $message = ts('No contacts found on do not call lists');
    return civicrm_api3_create_success($message, $params, 'Job', 'sync_dnc');
  }
}
