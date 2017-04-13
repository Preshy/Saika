<?php

/**
* Saika - The PHP Framework for KIDS
*
* Manages alerts aka feedback messages.
*
* @version 1.0
* @since 1.0
*/
class Alert
{
    /**
     * Alerts types
     *
     * @var array
     */
    public static $__saika_alert_types = array('error', 'notice', 'success', 'warning');

    /**
     * Session key prefix to store alerts
     *
     * @var string
     */
    public static $__saika_alert_session_prefix = '__saika_alerts.';

    /**
     * Add alert message(s)
     *
     * @param array|string $messages The messages as array. If it is a single one,
     *                         you can input it as a string.
     * @param string $type     The alert type. 'error', 'notice', 'success', 'warning'
     * @return boolean
     * @throws \InvalidArgumentException If $type is invalid
     */
    public static function add($messages, $type)
    {
        if (!self::isValidType($type)) {
            throw new \InvalidArgumentException('Invalid alert type!');
        }

        // If it is a single message, convert it to an array
        if (!is_array($messages)) {
            $messages = array($messages);
        }

        $key = sprintf(self::$__saika_alert_session_prefix . '%s', $type);
        $messages = array_merge(Session::get($key, array()), $messages);
        Session::set($key, $messages);
        return true;
    }

    /**
     * Read alert messages of a specific type, this will also delete the messages
     * from session to avoid duplicates
     *
     * @param string $type     The alert type. 'error', 'notice', 'success', 'warning'
     * @return array
     * @throws \InvalidArgumentException If $type is invalid
     */
    public static function read($type)
    {
        if (!self::isValidType($type)) {
            throw new \InvalidArgumentException('Invalid alert type!');
        }

        $key = sprintf(self::$__saika_alert_session_prefix . '%s', $type);
        $messages = Session::get($key, array());
        Session::remove($key);
        return $messages;
    }

    /**
     * Display alert messages of a specific type, this will also delete the messages
     * from session to avoid duplicates
     *
     * @param string $type   The alert type. 'error', 'notice', 'success', 'warning'
     * @param string $before HTML/Text to display before the messages.
     *                       Note: %type% will be replaced with alert type
     * @param string $before HTML/Text to display after the messages.
     *                       Note: %type% will be replaced with alert type
     * @return
     */
    public static function show(
        $type,
        $before = '<div class="alert alert-%type%">',
        $after = '</div>'
        ) {
        $alerts = self::read($type);
        // Why should i even continue?
        if (empty($alerts)) {
            return '';
        }
        $before = str_ireplace('%type%', $type, $before);
        $after = str_ireplace('%type%', $type, $after);
        echo $before;
        echo implode('<br>', $alerts);
        echo $after;
    }

    /**
     * Check if the provided alert type is valid
     *
     * @access private
     * @param string $type The alert type. 'error', 'notice', 'success', 'warning'
     * @return boolean
     */
    private static function isValidType($type)
    {
        $type = mb_strtolower($type);
        if (!in_array($type, self::$__saika_alert_types)) {
            return false;
        }
        return true;
    }
}
