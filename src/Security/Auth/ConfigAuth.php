<?php namespace Sintattica\Atk\Security\Auth;

use Sintattica\Atk\Core\Config;
use Sintattica\Atk\Security\SecurityManager;

/**
 * Driver for authentication and authorization using entries in the
 * configurationfile.
 *
 * See the methods in the atkConfig class for an explanation of how to add
 * users and privileges.
 *
 * Does not support authorization.
 *
 * @author Ivo Jansch <ivo@achievo.org>
 * @package atk
 * @subpackage security
 *
 */
class ConfigAuth extends AuthInterface
{

    /**
     * Authenticate a user.
     *
     * @param String $user The login of the user to authenticate.
     * @param String $passwd The password of the user. Note: if the canMd5
     *                       function of an implementation returns true,
     *                       $passwd will be passed as an md5 string.
     *
     * @return int SecurityManager::AUTH_SUCCESS - Authentication succesful
     *             SecurityManager::AUTH_MISMATCH - Authentication failed, wrong
     *                             user/password combination
     *             SecurityManager::AUTH_LOCKED - Account is locked, can not login
     *                           with current username.
     *             SecurityManager::AUTH_ERROR - Authentication failed due to some
     *                          error which cannot be solved by
     *                          just trying again. If you return
     *                          this value, you *must* also
     *                          fill the m_fatalError variable.
     */
    function validateUser($user, $passwd)
    {
        if ($user == "") {
            return SecurityManager::AUTH_UNVERIFIED;
        } // can't verify if we have no userid

        global $config_user;
        if ($user != "" && $passwd != "" && $config_user[$user]["password"] == $passwd) {
            return SecurityManager::AUTH_SUCCESS;
        } else {
            return SecurityManager::AUTH_MISMATCH;
        }
    }

    /**
     * Does the authentication method support md5 encoding of passwords?
     *
     * @return boolean True if md5 is always used. false if md5 is not
     *                 supported.
     *                 Drivers that support both md5 and cleartext passwords
     *                 can return Config::getGlobal("authentication_md5") to let the
     *                 application decide whether to use md5.
     */
    function canMd5()
    {
        return Config::getGlobal("authentication_md5");
    }

    /**
     * This function returns information about a user in an associative
     * array with the following elements:
     * "name" -> the userid (should normally be the same as the $user
     *           variable that gets passed to it.
     * "level" -> The level/group(s) to which this user belongs.
     * Specific implementations of the method may add more information if
     * necessary.
     *
     * @param String $user The login of the user to retrieve.
     * @return array Information about a user.
     */
    function getUser($user)
    {
        global $config_user;
        return Array("name" => $user, "level" => $config_user[$user]["level"]);
    }

    /**
     * This function returns the level/group(s) that are allowed to perform
     * the given action on a node.
     * @param String $node The full nodename of the node for which to check
     *                     the privilege. (modulename.nodename)
     * @param String $action The privilege to check.
     * @return mixed One (int) or more (array) entities that are allowed to
     *               perform the action.
     */
    function getEntity($node, $action)
    {
        global $config_access;

        $rights = $config_access[$node];

        $result = Array();

        for ($i = 0; $i < count($rights); $i++) {
            if ($rights[$i][$action] != "") {
                $result[] = $rights[$i][$action];
            }
            if ($rights[$i]["*"] != "") {
                $result[] = $rights[$i]["*"];
            }
        }

        return $result;
    }

    /**
     * This function returns the level/group(s) that are allowed to
     * view/edit a certain attribute of a given node.
     * @param String $node The full nodename of the node for which to check
     *                     attribute access.
     * @param String $attrib The name of the attribute to check
     * @param String $mode "view" or "edit"
     * @return array
     */
    function getAttribEntity($node, $attrib, $mode)
    {
        global $config_attribrestrict;

        // $entity is an array of entities that may do $mode on $node.$attrib.
        $entity = $config_attribrestrict[$node][$attrib][$mode];

        return $entity;
    }

    /**
     * This function returns a boolean that is true when the class allows the
     * resetting of the password of a user.
     * @deprecated Seems like this function is not used anymore
     *
     * @return false
     */
    function setPasswordAllowed()
    {
        return false;
    }

    /**
     * This function returns "get password" policy for current auth method
     *
     * @return int const
     */
    function getPasswordPolicy()
    {
        return self::PASSWORD_RETRIEVABLE;
    }

    /**
     * This function returns password or false, if password can't be retrieve/recreate
     *
     * @param string $username User for which the password should be regenerated
     *
     * @return mixed string with password or false
     */
    function getPassword($username)
    {
        if (isset($config_user[$username]["password"])) {
            return $config_user[$username]["password"];
        }
        return false;
    }

}


