<?php

use Coconnex\Utils\Handlers\AssetHandler;

$config_vars = "<style>";
$config_vars .= ":root {";
$config_vars .= "--search_col:" . $vars['colours']['search'] . ";";
$config_vars .= "--cartclr:" . $vars['colours']['in_cart'] . ";";
$config_vars .= "--selectedclr:" . $vars['colours']['selected'] . ";";
$config_vars .= "}";
$config_vars .= "</style>";

AssetHandler::add_asset("/assetscdn/floorplan/scripts/moveable.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/zoomable.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/panzoom.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/utils.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/cart.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/floorplan.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/stand_validations.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/stand_packages.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/stand_actions.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/popup.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/search_stands.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/fp_toolbar.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/stand_info.js", "script");
AssetHandler::add_asset("/assetscdn/floorplan/scripts/package_info.js", "script");
AssetHandler::add_asset("/assetscdn/fp_controls/scripts/fp_controls.js", "script");

AssetHandler::add_asset($config_vars, "style");

AssetHandler::add_asset("/assetscdn/floorplan/styles/site.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/floorplan.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/cart.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/moveable.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/popup.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/legends.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/search_stands.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/fp_toolbar.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/floorplan/styles/stand_info.css", "stylesheet");
AssetHandler::add_asset("/assetscdn/fp_controls/styles/fp_controls.css", "stylesheet");
