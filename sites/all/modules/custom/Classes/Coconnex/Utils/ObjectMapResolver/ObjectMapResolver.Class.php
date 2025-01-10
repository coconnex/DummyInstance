<?php

namespace Coconnex\Utils\ObjectMapResolver;

require_once(dirname(dirname(dirname(__FILE__))) . "/API/Base/Collections/CNXCollection.Collection.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/DBFactory/Vendors/Drupal/Dnode/Db.Class.php");

use Coconnex\API\Base\Collections\CNXCollection;
use Coconnex\DBFactory\Vendors\Drupal\Dnode\Db as Dnode;
use Exception;

class ObjectMapResolver
{
    protected $targets;
    protected $sources;
    protected $map;
    protected $reverse_map;

    /**
     * __construct
     *
     * @param  mixed $to
     * @param  mixed $from
     * @param  array $map
     * @param  bool $reverse_map
     * @return void
     */
    public function __construct($targets = null, $sources = null, $map = null, $reverse_map = false)
    {
        $this->targets = $targets;
        $this->sources = $sources;
        $this->map = $map;
        $this->reverse_map = $reverse_map;
        if ($reverse_map) $this->reverse_map();
    }

    /**
     * reverse_map
     *
     * @return void
     */
    public function reverse_map()
    {
        if (\is_array($this->map)) {
            $rev_map = array();
            foreach ($this->map as $trg => $src) {
                $rev_map[$src] = $trg;
            }
            $this->map = $rev_map;
        }
        debug($this->map);
    }

    /**
     * get_prop
     *
     * @param  mixed $o
     * @param  array $path
     * @return mixed $value
     */
    public function get_prop($path, &$o = null)
    {
        $o = ($o === null) ? $this->get_object($path, $this->sources) : $o;
        $r = $o;
        if ($r) {
            $i = 0;
            foreach ($path as $child) {
                if ($i == sizeof($path) - 1) {
                    if ($r instanceof CNXCollection || is_array($r)) {
                        $r = $r[$child];
                    } elseif ($r instanceof Dnode) {
                        $r = $r->get_prop($r->{$child});
                    } else {
                        $r = $r->{$child};
                    }
                } else {
                    $r = ($r instanceof CNXCollection || is_array($r)) ?  $r[$child] : $r->{$child};
                }
                $i++;
            }
        } else {
            throw new Exception("Object reference could not be resolved for source path " . implode("->", $path));
        }
        return $r;
    }

    /**
     * set_prop
     *
     * @param  mixed $o
     * @param  array $path
     * @param  mixed $value
     * @return void
     */
    public function set_prop($path, $value, &$o = null)
    {
        $o = ($o === null) ? $this->get_object($path, $this->targets) : $o;
        $r = $o;
        if ($r) {
            $i = 0;
            foreach ($path as $child) {
                if ($i == sizeof($path) - 1) {
                    if ($r instanceof CNXCollection || is_array($r)) {
                        $r[$child] = $value;
                    } elseif ($r instanceof Dnode) {
                        $r->set_prop($r->{$child}, $value);
                    } elseif(is_object($r)) {
                        $r->{$child} = $value;
                    }
                } else {
                    $r = ($r instanceof CNXCollection || is_array($r)) ?  $r[$child] : $r->{$child};
                }
                $i++;
            }
        } else {
            throw new Exception("Object reference could not be resolved for target path " . implode("->", $path));
        }
    }

    /**
     * get_object
     *
     * @param  mixed $str_path
     * @param  mixed $obj_list
     * @return void
     */
    public function get_object(&$path, &$obj_list)
    {
        $match = array();
        if (\preg_match("/\{(.*)\}/", $path[0], $match)) {
            if (strlen($match[1]) > 0) {
                unset($path[0]);
                return $obj_list[$match[1]];
            }
        }
        return null;
    }

    public function set_target()
    {
        $trg_keys = \array_keys($this->targets);
        $first_trg_key = $trg_keys[0];
        $src_keys = \array_keys($this->sources);
        $first_src_key = $src_keys[0];

        foreach ($this->map as $trg => $src) {
            $value = (sizeof($this->sources) > 0) ? $this->get_prop(explode("->", $src)) : $this->get_prop(explode("->", $src), $this->sources[$first_src_key]);

            if (sizeof($this->targets) > 1) {
                $this->set_prop(explode("->", $trg), $value);
            } else {
                $this->set_prop(explode("->", $trg), $value, $this->targets[$first_trg_key]);
            }
        }
    }
}
