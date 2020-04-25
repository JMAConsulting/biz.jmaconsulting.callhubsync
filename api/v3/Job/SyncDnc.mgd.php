<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return array (
  0 => 
  array (
    'name' => 'Cron:Job.SyncDnc',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'CallHub Do not call list Sync',
      'description' => 'One way sync of all do not call lists from CallHub to CiviCRM',
      'run_frequency' => 'Daily',
      'api_entity' => 'Job',
      'api_action' => 'SyncDnc',
      'parameters' => '',
    ),
  ),
);
