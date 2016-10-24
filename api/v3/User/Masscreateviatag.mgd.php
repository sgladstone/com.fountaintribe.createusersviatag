<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'Cron:User.Masscreateviatag',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Mass create users via tag',
      'description' => 'Mass create users via tag',
      'run_frequency' => 'Always',
      'api_entity' => 'User',
      'api_action' => 'Masscreateviatag',
      'parameters' => 'Change this to "tagname=something"',
    ),
  ),
);