<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class Form extends Widget
{
    /**
     * jQuery Selector to init element!
     * @var string
     */
    public $selector = '#jqxForm';

    /**
     * Widget instance name!
     * @var string
     */
    public $varInstanceName  = 'jqxFormMain';

    /**
     * Form's props
     * @var array
     */
    protected $_props = [
        // Sets the widget's theme.
        'theme' => 'bootstrap',
    ];

    /**
     * Form's props
     * @var array
     */
    protected $_events = [
        // Bind to the create event by type: jqxForm.
        'create' => <<<JS
    function(event) { $(document).trigger('jqxFormCreated', [event]); }
JS
,
        // Bind to the pin event by type: jqxForm.
        'pin' => Helper::UNDEF,
        // Bind to the resize event by type: jqxForm.
        'resize' => Helper::UNDEF,
        // This event is triggered when a group has been unpinned.
        'unpin' => Helper::UNDEF,
    ];

    /**
     * @var Item
     */
    protected $item;

    /**
     * @var array Items
     */
    protected $_items = [];

    /**
     * Add item
     *
     * @param array $item
     * @param Closure $callback Add sub item?
     * @return this
     */
    public function addItem(array $item, $callback = null)
    {
        // Add items
        $item = new Item($item);
        $this->_items[] = $item;
        // Case: add sub items
        if ($callback instanceof Closure) {
            $callback($item);
        }
        return $this;
    }

    /**
     * Get items
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }

    /**
     * @return string
     */
    public function html($content = '') {
        return "<div id=\"jqxForm\">{$content}</div>";
    }

    /**
     * Overloading methods
     */
    public function __toString()
    {
        // JS of js data
        $jsDataJS = $this->getJsDataJS();
        // JS of events
        $eventsJS = $this->getEventsJS();
        // JS init
        $props = array_replace($this->_props, [
            
        ]);
        $jqxFormJS = Helper::toJson($props);
        // Return
        $script = <<<JS
(function($){
    // Form
    var {$this->varInstanceName} = $('{$this->selector}');
    // +++ data
    {$jsDataJS}
    // +++ events
    {$eventsJS}
    // +++ init widget
    {$this->varInstanceName}.jqxForm({$jqxFormJS});
})(jQuery);
JS;
        return trim($script);
    }
}
