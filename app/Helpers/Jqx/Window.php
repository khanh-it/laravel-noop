<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class Window extends Widget
{
    /**
     * jQuery Selector to init element!
     * @var string
     */
    public $selector = '#jqxWindow';

    /**
     * Widget instance name!
     * @var string
     */
    public $varInstanceName  = 'jqxWindow';

    /**
     * Window's props
     * @var array
     */
    protected $_props = [
        // Sets the widget's theme.
        'theme' => 'bootstrap',
        // 
        'title' => Helper::UNDEF,
        // 
        'content' => Helper::UNDEF,
        // 
        'width' => Helper::UNDEF,
        // 
        'minWidth' => Helper::UNDEF,
        // 
        'maxWidth' => Helper::UNDEF,
        // 
        'height' => Helper::UNDEF,
        // 
        'minHeight' => Helper::UNDEF,
        // 
        'maxHeight' => Helper::UNDEF,
        // 
        'autoOpen' => false,
        // 
        'closeButtonAction' => 'hide',
        // 
        'isModal' => Helper::UNDEF,
        // 
        'showCollapseButton' => true,
    ];

    /**
     * Window's events
     * @var array
     */
    protected $_events = [
        // Bind to the create event by type: jqxLayout.
        'created' => <<<JS
    function(event) { $(event.target).css('visibility', ''); }
JS
,
    ];

    /**
     * @param array $options An array of content
     * @return string
     */
    public function html(array $content = array()) {
        $id = $content['id'];
        if (is_null($id)) {
            if (strpos($this->selector, '#') !== false) {
                $id = str_replace('#', '', $this->selector);
            }
        }
        $id = $id ?: 'jqxWindow';
        return '<div id="' . $id . '" style="visibility: hidden;">'
            . "<div>{$content['title']}</div>"
            . '<div>'
                . '<div class="jqx-window-inner">'
                    . '<div class="jqx-window-inner-body">'
                        . $content['body']
                    . '</div>'
                    . '<div class="jqx-window-inner-footer">'
                        . $content['footer']
                    . '</div>'
                . '</div>'
            . '</div>'
            . '</div>'
        ;
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
        $jqxWindowJS = Helper::toJson($props);
        // Return
        $script = <<<JS
(function($){
    // Window
    var {$this->varInstanceName} = $('{$this->selector}');
    // +++ data
    {$jsDataJS}
    // +++ events
    {$eventsJS}
    // +++ init widget
    {$this->varInstanceName}.jqxWindow({$jqxWindowJS});
})(jQuery);
JS;
        return trim($script);
    }
}
