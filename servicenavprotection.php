<?php

require_once 'servicenavprotection.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function servicenavprotection_civicrm_config(&$config) {
  _servicenavprotection_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function servicenavprotection_civicrm_xmlMenu(&$files) {
  _servicenavprotection_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function servicenavprotection_civicrm_install() {
  _servicenavprotection_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function servicenavprotection_civicrm_uninstall() {
  _servicenavprotection_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function servicenavprotection_civicrm_enable() {
  _servicenavprotection_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function servicenavprotection_civicrm_disable() {
  _servicenavprotection_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function servicenavprotection_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _servicenavprotection_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function servicenavprotection_civicrm_managed(&$entities) {
  _servicenavprotection_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function servicenavprotection_civicrm_caseTypes(&$caseTypes) {
  _servicenavprotection_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function servicenavprotection_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _servicenavprotection_civix_civicrm_alterSettingsFolders($metaDataFolders);
}


/**
 * Implementation of hook_civicrm_buildForm
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function servicenavprotection_civicrm_buildForm($formName, &$form) {
  // Restrict activity types for forms.
  if ($formName == "CRM_Activity_Form_Activity") {
    // Restrict list of activity types available on activity creation form.
    $disallowedActivities = [22, 136, 137];
    if ($form->_action & CRM_Core_Action::ADD) {
      $activityOptions = array_diff_key(CRM_Core_PseudoConstant::ActivityType(FALSE), array_flip($disallowedActivities));

      $form->add('select', 'activity_type_id', ts('Activity Type'),
        array('' => '- ' . ts('select') . ' -') + $activityOptions,
        FALSE, array(
          'onchange' => "CRM.buildCustomData( 'Activity', this.value );",
          'class' => 'crm-select2 required',
        )
      );

      // Restrict follow up activities too.
      $form->add('select', 'followup_activity_type_id', ts('Followup Activity'),
        array('' => '- ' . ts('select') . ' -') + $activityOptions,
        FALSE, array(
          'class' => 'crm-select2',
        )
      );
    }
    if ($form->_action & CRM_Core_Action::UPDATE) {
      if ($unsetVal = array_search($form->_activityTypeId, $disallowedActivities)) {
        unset($disallowedActivities[$unsetVal]);
      }
      $activityOptions = array_diff_key(CRM_Core_PseudoConstant::ActivityType(FALSE), array_flip($disallowedActivities));

      $form->add('select', 'activity_type_id', ts('Activity Type'),
        array('' => '- ' . ts('select') . ' -') + $activityOptions,
        FALSE, array(
          'onchange' => "CRM.buildCustomData( 'Activity', this.value );",
          'class' => 'crm-select2 required',
        )
      );

      // Restrict follow up activities too.
      $form->add('select', 'followup_activity_type_id', ts('Followup Activity'),
        array('' => '- ' . ts('select') . ' -') + $activityOptions,
        FALSE, array(
          'class' => 'crm-select2',
        )
      );
    }
  }
  // Restrict activity types available in the "New Activity" creation list on contact summary page.
  if ($formName == "CRM_Activity_Form_ActivityLinks") {
    $disallowedActivities = [22, 136, 137];
    $activities = CRM_Core_PseudoConstant::activityType(TRUE, TRUE, FALSE, 'name', TRUE);
    $activityOptions = array_diff_key($activities, array_flip($disallowedActivities));
    $activityTypes = CRM_Core_Smarty::singleton()->get_template_vars('activityTypes');
    foreach ($activityTypes as $key => $activity) {
      if (!array_key_exists($activity['value'], $activityOptions)) {
        unset($activityTypes[$key]);
      }
    }
    $form->assign('activityTypes', $activityTypes);
  }
}
