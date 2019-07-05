<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class Menu extends Widget
{
    /**
     * @var string
     */
    public $varInstanceName = 'jqxMenuMain';

    /**
     * Props
     * @var array
     */
    protected $_props = [
        // Sets or gets the duration of the show animation.
        'animationShowDuration' => Helper::UNDEF,
        // Sets or gets the duration of the hide animation.
        'animationHideDuration' => Helper::UNDEF,
        // Sets or gets the delay before the start of the hide animation.
        'animationHideDelay' => Helper::UNDEF,
        // Sets or gets the delay before the start of the show animation.
        'animationShowDelay' => Helper::UNDEF,
        // Sets or gets the time interval after which all opened items will be closed.
        // When you open a new sub menu, the interval is cleared. 
        // If you want to disable this automatic closing behavior of the jqxMenu, you need to set the autoCloseInterval property to 0.
        'autoCloseInterval' => Helper::UNDEF,
        // Auto-Sizes the jqxMenu's main items when the menu's mode is 'horizontal'
        'autoSizeMainItems' => Helper::UNDEF,
        // Set the autoCloseOnClick property.
        'autoCloseOnClick' => Helper::UNDEF,
        // Opens the Context Menu when the right-mouse button is pressed.
        // When this property is set to false, the Open and Close functions can be used to open and close the Context Menu.
        'autoOpenPopup' => Helper::UNDEF,
        // Opens the top level menu items when the user hovers them.
        'autoOpen' => Helper::UNDEF,
        // Opens an item after a click by the user.
        'clickToOpen' => Helper::UNDEF,
        // Enables or disables the jqxMenu.
        'disabled' => Helper::UNDEF,
        // Enables or disables the hover state
        'enableHover' => Helper::UNDEF,
        // Sets or gets the animation's easing to one of the JQuery's supported easings.
        'easing' => Helper::UNDEF,
        // Sets or gets the jqxMenu's height.
        'height' => Helper::UNDEF,
        // Enables or disables the jqxMenu's keyboard navigation
        'keyboardNavigation' => Helper::UNDEF,
        // Set the minimizeWidth property.
        'minimizeWidth' => Helper::UNDEF,
        // Sets or gets the menu's display mode
        'mode' => Helper::UNDEF,
        // Sets or gets the popup's z-index.
        'popupZIndex' => Helper::UNDEF,
        // Sets or getsa value indicating whether widget's elements are aligned to support locales using right-to-left fonts.
        'rtl' => Helper::UNDEF,
        // Specifies the jqxMenu's data source. Use this property to populate the jqxMenu.
        /* Each menu item may have following fields: 
            label - item's label.
            value - item's value.
            html - item's html. The html to be displayed in the item.
            id - item's id.
            items - array of sub items.
            subMenuWidth - sets the sub menu's width.
            disabled - determines whether the item is enabled/disabled. */
        'source' => Helper::UNDEF,
        // Sets or gets the popup's z-index.
        'theme' => 'bootstrap',
        // Sets or gets the jqxMenu's width.
        'width' => Helper::UNDEF,
    ];   

    /**
     * Events
     * @var array
     */
    protected $_events = [
        // This event is triggered when any of the jqxMenu Sub Menus is closed.
        'closed' => Helper::UNDEF,
        // This event is triggered when a menu item is clicked.
        'itemclick' => Helper::UNDEF,
        // Bind to the initialized event by type: jqxMenu.
        'initialized' => Helper::UNDEF,
        // This event is triggered when any of the jqxMenu Sub Menus is displayed.
        'shown' => Helper::UNDEF,
    ];

    /**
     * Overloading methods
     */
    public function __toString()
    {
        // JS of events
        $eventsJS = $this->getEventsJS();
        // JS init
        $propsJS = $this->getPropsJS();
        // Return
        $script = <<<JS
(function(){
return (function($){
    // Layout
    var {$this->varInstanceName} = $('{$this->selector}');
    // +++ events
    {$eventsJS}
    // +++ init
    {$this->varInstanceName}.jqxMenu({$propsJS});
    // +++ public
    return {$this->varInstanceName};
})(jQuery);
})
JS;
        return trim($script);
    }
}
