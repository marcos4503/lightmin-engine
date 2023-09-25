<?php

/*
 * This script has the task of assembling the PHP code that comes before the code written by the user in the "settings.php" file and
 * thus abstracting the user from the source code behind the "settings.php" file. Furthermore, this file defines all existing configuration
 * variables and their respective default values.
*/

//Include the needed library
include_once("engine/external-php-libraries/backend-response-builder-1-0-0.php");

//Prepare the response
$settings = new ResponseBuilder();
$settings->SetSuccessHeader(true);

/////////////////////////////////////////// SETTINGS VARIABLE DECLARATIONS ///////////////////////////////////////////

//Client variables...
$settings->DeclareVariablePrimitive("websiteFaviconNoNotificationsUri", "STRING");
$settings->DeclareVariablePrimitive("websiteFaviconWithNotificationsUri", "STRING");
$settings->DeclareVariablePrimitive("websiteLang", "STRING");
$settings->DeclareVariablePrimitive("websiteCharSet", "STRING");
$settings->DeclareVariablePrimitive("websitePrimaryFontFamily", "STRING");
$settings->DeclareVariablePrimitive("websiteSecondaryFontFamily", "STRING");
$settings->DeclareVariablePrimitive("websiteFontSizePx", "INT");
$settings->DeclareVariablePrimitive("browserColor", "STRING");
$settings->DeclareVariablePrimitive("loadScreenLogoUri", "STRING");
$settings->DeclareVariablePrimitive("loadScreenMessage", "STRING");
$settings->DeclareVariablePrimitive("loadScreenBackgroundColorHexGradientStart", "STRING");
$settings->DeclareVariablePrimitive("loadScreenBackgroundColorHexGradientEnd", "STRING");
$settings->DeclareVariablePrimitive("loadScreenStyledBackgroundColorHex", "STRING");
$settings->DeclareVariablePrimitive("loadScreenForegroundColorHex", "STRING");
$settings->DeclareVariablePrimitive("loadScreenBackgroundColorHex", "STRING");
$settings->DeclareVariablePrimitive("loadScreenErrorMessage", "STRING");
$settings->DeclareVariablePrimitive("loadScreenErrorButtonMessage", "STRING");
$settings->DeclareVariablePrimitive("showTextSelectionHighlight", "BOOL");

////////////////////////////////////////// SETTINGS VARIABLE DEFAULT VALUES //////////////////////////////////////////

//Client variables...
$settings->SetVariablePrimitiveValue("websiteFaviconNoNotificationsUri", "engine/medias/icons/lightmin-no-notifications.ico");
$settings->SetVariablePrimitiveValue("websiteFaviconWithNotificationsUri", "engine/medias/icons/lightmin-with-notifications.ico");
$settings->SetVariablePrimitiveValue("websiteLang", "en-US");
$settings->SetVariablePrimitiveValue("websiteCharSet", "UTF-8");
$settings->SetVariablePrimitiveValue("websitePrimaryFontFamily", "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'");
$settings->SetVariablePrimitiveValue("websiteSecondaryFontFamily", "Helvetica, sans-serif");
$settings->SetVariablePrimitiveValue("websiteFontSizePx", 16);
$settings->SetVariablePrimitiveValue("browserColor", "#0055A5");
$settings->SetVariablePrimitiveValue("loadScreenLogoUri", "engine/medias/images/lightmin-load-logo.png");
$settings->SetVariablePrimitiveValue("loadScreenMessage", "Loading");
$settings->SetVariablePrimitiveValue("loadScreenBackgroundColorHexGradientStart", "#f5f5f5");
$settings->SetVariablePrimitiveValue("loadScreenBackgroundColorHexGradientEnd", "#ffffff");
$settings->SetVariablePrimitiveValue("loadScreenStyledBackgroundColorHex", "#c9c9c9");
$settings->SetVariablePrimitiveValue("loadScreenForegroundColorHex", "#6d90c9");
$settings->SetVariablePrimitiveValue("loadScreenBackgroundColorHex", "#394c6b");
$settings->SetVariablePrimitiveValue("loadScreenErrorMessage", "There was a problem on load the Website. Please check your Internet connection and try again!");
$settings->SetVariablePrimitiveValue("loadScreenErrorButtonMessage", "Try Again");
$settings->SetVariablePrimitiveValue("showTextSelectionHighlight", false);

?>