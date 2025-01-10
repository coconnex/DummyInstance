<?php

namespace Coconnex\Utils\Handlers;

class AssetHandler
{
    public static $assets = null;
    public static $assets_cdn_base_path;
    public static $cache_control_string;
    const allowed_head_types = "title,base,link,meta,script,style";
    const allowed_link_relations = "alternate,author,dns-prefetch,help,icon,license,next,pingback,preconnect,prefetch,preload,prerender,prev,search,stylesheet";
    const allowed_body_types = "link,script,style";

    public static function assert($args, $base_path = null)
    {
        if (!$base_path) $base_path = self::$assets_cdn_base_path;
        $asset_details = self::get_asset($args, $base_path);
        if (!is_array($asset_details)) {
            header("HTTP/1.0 404 Not Found");
            die();
        }

        $etag = $asset_details['file'] . "-" . filemtime($asset_details['abs_path'] . "/" . $asset_details['file']);

        switch ($asset_details['type']) {
            case "css":
                header('Content-Type: text/css');
                break;
            case "scss":
                header('Content-Type: text/scss');
                break;
            case "module":
            case "js":
                header('Content-Type: application/javascript');
                break;
            case "png":
                header('Content-Type: image/png');
                break;
            case "gif":
                header('Content-Type: image/gif');
                break;
            case "jpg":
            case "jpeg":
                header('Content-Type: image/jpeg');
                break;
            default:
                header('Content-Type: text/plain');
                break;
        }
        header('Content-Transfer-Encoding: gzip, deflate');
        header('Expires:0');
        header('Cache-Control: max-age=86400');
        header('ETag: ' . $etag);
        header('Pragma: public');
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
                header('HTTP/1.1 304 Not Modified', true, 304);
                exit;
            }
        }
        ob_clean();
        readfile($asset_details['abs_path'] . "/" . $asset_details['file']);
        exit;
    }

    public static function add_asset($res, $type, $attrs = null, $is_body = false)
    {
        if (!is_array(self::$assets)) self::set_common_assets();

        $rendered_asset = self::get_rendered_asset($type, $res, $attrs);
        if ($is_body) {
            self::$assets['body'][$type][] = $rendered_asset;
        } else {
            self::$assets['head'][$type][] = $rendered_asset;
        }
    }

    public static function render_assets($is_body = false)
    {
        if (!is_array(self::$assets)) self::set_common_assets();
        $assets = ($is_body) ? self::$assets['body'] : self::$assets['head'];
        $html = "";
        if (\is_array($assets)) {
            foreach ($assets as $asset_grp) {
                $html .= ((\is_array(($asset_grp))) ? implode("\n", $asset_grp) : $asset_grp) . "\n";
            }
        }
        echo $html;
    }

    protected static function get_rendered_asset($type, $res, $attrs = null)
    {
        $attrs = self::get_attr_string($attrs);
        switch ($type) {
            case "alternate":
            case "author":
            case "dns-prefetch":
            case "help":
            case "icon":
            case "license":
            case "next":
            case "pingback":
            case "preconnect":
            case "prefetch":
            case "preload":
            case "prerender":
            case "prev":
            case "search":
            case "stylesheet":
                return \sprintf('<link rel="%s" href="%s%s" %s />', $type, $res, self::$cache_control_string, $attrs);
                break;
            case "script":
                return \sprintf('<script src="%s%s" %s></script>', $res, self::$cache_control_string, $attrs);
                break;
            case "title":
                return \sprintf('<title %s>%s</title>', $attrs, $res);
                break;
            case "meta":
                return \sprintf('<meta %s />', $attrs);
                break;
            case "base":
                return \sprintf('<base href="%s" %s />', $res, $attrs);
                break;
            case "style":
            case "element":
            default:
                return  $res;
                break;
        }
    }

    protected static function get_asset($args, $base_path)
    {
        $dir_path = $base_path;
        $asset['rel_path'] = null;
        $asset['abs_path'] = null;
        $asset['file'] = null;
        $asset['type'] = null;
        if (sizeof($args) >= 3) {
            for ($i = 1; $i < sizeof($args) - 1; $i++) {
                $asset['rel_path'] .= (($i > 1) ? "/" : "") . $args[$i];
            }
            $subject = trim($args[sizeof($args) - 1]);
            $pattern = "/^.*\.(css|scss|module|js|gif|png|jpg|jpeg)$/";
            $match = preg_match($pattern, $subject, $matches);
            $asset['file'] = ($match) ? $subject : null;
            $asset['type'] = ($match) ? $matches[1] : null;
            if ($asset['rel_path'] === null ||  $asset['file'] === null) return null;
            $asset['abs_path'] = $dir_path . "/" . $asset['rel_path'];
            if (!file_exists($asset['abs_path'])) return null;
            return $asset;
        }
        return null;
    }

    protected static function get_attr_string($attrs)
    {
        if (!is_array($attrs)) return $attrs;
        $attr_string = "";
        foreach ($attrs as $key => $value) {
            $attr_string .= \sprintf('%s = "%s"', $key, $value) . " ";
        }
        return trim($attr_string);
    }

    public static function set_common_assets()
    {
        $assets = \parse_ini_file(self::$assets_cdn_base_path . "/global_assets/global_assets.ini", true);
        if ($assets) {
            if (isset($assets['head'])) {
                foreach ($assets['head'] as $type => $res_grp) {
                    if (\is_array($res_grp)) {
                        for ($i = 0; $i < sizeof($res_grp); $i++) {
                            self::$assets['head'][$type][] = self::get_rendered_asset($type, $res_grp[$i]);
                        }
                        $i = null;
                    }
                }
                $type = null;
                $res_grp = null;
            }
            if (isset($assets['body'])) {
                foreach ($assets['body'] as $type => $res_grp) {
                    if (\is_array($res_grp)) {
                        for ($i = 0; $i < sizeof($res_grp); $i++) {
                            self::$assets['body'][$type][] = self::get_rendered_asset($type, $res_grp[$i]);
                        }
                        $i = null;
                    }
                }
                $type = null;
                $res_grp = null;
            }
        }
    }

    protected static function scan_global_assets_folder()
    {
        if (!isset(self::$assets_cdn_base_path)) return false;
        $glbl_folder = self::$assets_cdn_base_path . "/global_assets";
        $head_folder = $glbl_folder . "/head_assets";
        $styles_folder = $head_folder . "/styles";
        $scripts_folder = $head_folder . "/scripts";
        if (\is_dir($styles_folder)) {
            echo "hi";
            if ($open_dir = \opendir($styles_folder)) {
                while (($file = readdir($open_dir)) !== false) {
                    $f_type = filetype($styles_folder . $file);
                    $f_name = $file;
                    if ($f_type !== 'dir') {
                    }
                }
                closedir($open_dir);
            }
        }
    }
}
