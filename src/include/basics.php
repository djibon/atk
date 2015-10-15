<?php
/**
 * This file is part of the ATK distribution on GitHub.
 * Detailed copyright and licensing information can be found
 * in the doc/COPYRIGHT and doc/LICENSE files which should be
 * included in the distribution.
 *
 * @package atk
 * @subpackage include
 *
 * @copyright (c)2000-2004 Ibuildings.nl BV
 * @license http://www.achievo.org/atk/licensing ATK Open Source License
 *
 * @version $Revision$
 * $Id$
 */
/** BASIC ATK INCLUDES * */
/** Gives you configs and the atktools * */
require_once($GLOBALS['config_atkroot'] . 'atk/include/globals.php');
require_once($GLOBALS['config_atkroot'] . 'atk/utils/class.atkclassloader.php');
require_once($GLOBALS['config_atkroot'] . 'atk/class.atktools.php');
require_once($GLOBALS['config_atkroot'] . 'atk/include/configs.php');
require_once($GLOBALS['config_atkroot'] . 'atk/utils/class.atkstring.php');


$locale = Tools::atktext('locale', 'atk');
if ($locale != null) {
    setlocale(LC_TIME, $locale);
}
