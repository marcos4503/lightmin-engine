<?php
//Include the needed files on Settings...
include_once("engine/lightmin-engine-common.php");
include_once("engine/lightmin-engine-settings-start.php");

// ┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
// ├──────────────────────────────────────────────────────────────      SETTINGS      ─────────────────────────────────────────────────────────────┤
// ├───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
// ├────────────────────────────────────────────────   ====> [ START EDITING BELOW HERE! ] <====   ────────────────────────────────────────────────┤
// └───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

/*
 * Set variables to desired values to configure aspects of your Website in Engine. Each variable you define here will override the default value of
 * the respective configuration variable in the Engine. Consult the documentation for more details and to find out about all existing
 * configurable variables.
*/

//Defined settings for this Website...
$settings->SetVariablePrimitiveValue("websiteBaseTitle", "Example Website");

// ┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
// ├────────────────────────────────────────────────   ====> [ STOP EDITING AFTER HERE! ] <====   ─────────────────────────────────────────────────┤
// └───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

//Finish the Settings including needed files...
include_once("engine/lightmin-engine-settings-end.php");
?>