<?php

namespace App\Helpers\Jqx\Layout;

use App\Helpers\Jqx\Helper;
use JsonSerializable;
use Closure;

/**
 * @author
 */
class Item implements JsonSerializable
{
    /**
     * @var array props
     */
    protected $_props = [
        // layoutGroup | documentGroup | documentPanel | tabbedGroup | layoutPanel
        'type' => '',
        // horizontal | vertical
        'orientation' => Helper::UNDEF,
        //
        'width' => Helper::UNDEF,
        //
        'minWidth' => Helper::UNDEF,
        //
        'height' => Helper::UNDEF,
        //
        'minHeight' => Helper::UNDEF,
        //
        'pinnedHeight' => Helper::UNDEF,
        //
        'title' => Helper::UNDEF,
        //
        'contentContainer' => Helper::UNDEF,
        //
        'initContent' => Helper::UNDEF,
        // 
        'selected' => Helper::UNDEF,
        //
        'items' => [],
    ];

    /**
     * Class's constructor
     * @param array|null $props
     */
    public function __construct($props = null)
    {
        // Set data
        if (is_array($props)) {
            $this->_props = array_replace(
                $this->_props,
                array_intersect_key($props, $this->_props)
            );
        }
    }

    /**
     * Add item
     * @param array $item
     * @param Closure $callback Add sub items?
     * @return this
     */
    public function addItem(array $item, $callback = null)
    {
        // Add items
        $item = new self($item);
        $this->_props['items'][] = Helper::rmUndef($item);
        // Case: add sub items
        if ($callback instanceof Closure) {
            $callback($item);
        }
        return $this;
    }

    /**
     * Overloading methods
     * @return mixed
     */
    public function jsonSerialize() {
        return Helper::rmUndef($this->_props);
    }
}
