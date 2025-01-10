<?php
require_once(dirname(dirname(dirname(__FILE__))) . "/modules/custom/Classes/Coconnex/Utils/Handlers/MessageHandler.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/modules/custom/Classes/Coconnex/Utils/Handlers/ProcessingPopupHandler.Class.php");

use Coconnex\Utils\Handlers\MessageHandler;

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to STARTERKIT_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: STARTERKIT_breadcrumb()
 *
 *   where STARTERKIT is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('STARTERKIT_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */
function exhibitor_zone_preprocess_user_login(&$variables)
{
  // $variables['intro_text'] = t('This is my awesome login form');
  // echo "<pre>"; print_r($variables['form']); exit;
  $variables['form']['name']['#title'] = 'Email Address';
  $variables['form']['name']['#description'] = '';
  $variables['form']['name']['#prefix'] = '<div>';
  $variables['form']['name']['#attributes'] = array('placeholder' => 'Please provide your email address', 'class' => 'login-placeholder');
  // $variables['form']['name']['#attributes']['onFocus'] = 'if(this.value=="") this.setAttribute("placeholder","");';
  // $variables['form']['name']['#attributes']['onBlur'] = 'if(this.value=="") this.setAttribute("placeholder","Username");';
  $variables['form']['name']['#suffix'] = '<div id="uname_error" class="inline_error"></div></div>';

  $variables['form']['pass']['#title'] = 'Password';
  $variables['form']['pass']['#description'] = '';
  $variables['form']['pass']['#prefix'] = '<div>';
  $variables['form']['pass']['#attributes'] = array('placeholder' => 'Please provide secret password', 'class' => 'login-placeholder');
  // $variables['form']['pass']['#attributes']['onFocus'] = 'if(this.value=="") this.setAttribute("placeholder","");';
  // $variables['form']['pass']['#attributes']['onBlur'] = 'if(this.value=="") this.setAttribute("placeholder","Password");';
  $variables['form']['pass']['#suffix'] = '<div id="pass_error" class="inline_error"></div></div>';

  $variables['form']['submit']['#prefix'] = '<div class="login-button">';
  // $variables['form']['submit']['#attributes'] = array('class' => 'btn btn-deep-orange');
  $variables['form']['submit']['#suffix'] = '</div>';

  $variables['rendered'] = drupal_render($variables['form']);
}

/**
 * Implementation of HOOK_theme().
 */
function exhibitor_zone_theme(&$existing, $type, $theme, $path)
{
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  $hooks['user_login'] = array(
    'template' => 'templates/user_login',
    'type' => MENU_CALLBACK,
    'arguments' => array('form' => NULL)
  );

  $hooks['user_pass'] = array(
    'template' => 'templates/user_pass',
    'type' => MENU_CALLBACK,
    'arguments' => array('form' => NULL)
  );

  $hooks['user_pass_reset'] = array(
    'template' => 'templates/user_pass_reset',
    'type' => MENU_CALLBACK,
    'arguments' => array('form' => NULL)
  );
  // @TODO: Needs detailed comments. Patches welcome!
  // debug($hooks,1);
  return $hooks;
}


/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_page(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_node(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

function exhibitor_zone_status_messages($display = NULL)
{
  MessageHandler::$message_tpl_folder_path = dirname(__FILE__) . "/templates/";
  MessageHandler::$message_tpl_file = "messages.tpl.php";
  if (isset($_SESSION['messages'])) {
    MessageHandler::merge($_SESSION['messages']);
    unset($_SESSION['messages']);
  }
}
