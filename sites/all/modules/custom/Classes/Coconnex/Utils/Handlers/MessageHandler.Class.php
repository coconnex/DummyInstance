<?php

namespace Coconnex\Utils\Handlers;

require_once(dirname(__FILE__) . "/TemplateHandler.Class.php");

use Coconnex\Utils\Handlers\TemplateHandler;

class MessageHandler
{
    public static $message_tpl_folder_path;
    public static $message_tpl_file;
    public static $messages;


    public static function add($message, $type = "status", $repeat = true)
    {
        if ($message) {
            if (!isset(self::$messages)) {
                self::$messages = array();
            }
            if (!isset(self::$messages[$type])) {
                self::$messages[$type] = array();
            }
            if ($repeat || !in_array($message, $_SESSION['messages'][$type])) {
                self::$messages[$type][] = $message;
            }
        }
    }

    public static function get($output = 'ARRAY')
    {
        if (isset(self::$messages)) {
            switch ($output) {
                case "JSON":
                    return \json_encode(self::$messages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    break;
                default:
                    return self::$messages;
                    break;
            }
        }
        return NULL;
    }

    public static function merge($messages)
    {
        if (!\is_array(self::$messages)) self::$messages = array();
        if (\is_array($messages)) self::$messages = \array_merge_recursive(self::$messages, $messages);
    }

    public static function clear()
    {
        self::$messages = NULL;
    }

    public static function render()
    {
        $msg_json = MessageHandler::get("JSON");
        if ($msg_json) {
            $vars['script'] = \sprintf("<script>messages.exists = %s</script>", is_array(self::$messages));
            print self::get_html($vars);
            self::clear();
        }
    }

    protected static function get_html($vars = array())
    {
        $file = self::$message_tpl_folder_path . self::$message_tpl_file;
        if (\file_exists($file)) {
            $vars['messages'] = self::$messages;
            return TemplateHandler::applyTemplateFile(self::$message_tpl_folder_path, self::$message_tpl_file, $vars);
        }
    }
}
