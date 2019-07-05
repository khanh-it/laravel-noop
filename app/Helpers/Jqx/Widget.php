<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class Widget
{
    /**
     * jQuery Selector to init widget!
     * @var string
     */
    public $selector = '';

    /**
     * Widget instance name!
     * @var string
     */
    public $varInstanceName  = '';

    /**
     * Widget's props
     * @var array
     */
    protected $_events = [];

    /**
     * Widget's js data
     * @var array
     */
    protected $_jsdata = [];

    /**
     * Class's constructor
     * @param array|null $options An array of options
     */
    public function __construct(array $options = array())
    {
        // Selector
        if (is_string($options['selector'])) {
            $this->selector = $options['selector'];
        }
        // Set data
        $this->setProps($options['props']);
        $this->setEvents($options['events']);
    }

    /**
     * Set widget's props
     * @return void
     */
    public function setProps($props = null) {
        if (is_array($props)) {
            $this->_props = array_replace(
                $this->_props,
                array_intersect_key($props, $this->_props)
            );
        }
        return $this;
    }

    /**
     * Get data props
     * @return string
     */
    public function getProps()
    {
        return $this->_props;
    }

    /**
     * Get data props as json
     * @return string
     */
    public function getPropsJS()
    {
        return Helper::toJson($this->_props);
    }

    /**
     * Set widget's events
     * @return void
     */
    public function setEvents($events = null) {
        if (is_array($events)) {
            $this->_events = array_replace(
                $this->_events,
                array_intersect_key($events, $this->_events)
            );
        }
        return $this;
    }

    /**
     * Get widget's events
     * @return string
     */
    public function getEvents()
    {
        return $this->_events;
    }

    /**
     *
     * @return string
     */
    public function getEventsJS()
    {
        //
        $events = Helper::rmUndef($this->_events);
        $eventsJS = [];
        if (!empty($events)) {
            foreach ($events as $name => $event) {
                $event = trim($event);
                $eventsJS[] = "{$this->varInstanceName}.on('{$name}', {$event});";
            }
        }
        // Return
        return empty($eventsJS) ? '' : implode(PHP_EOL, $eventsJS);
    }

    /**
     * Set widget's js data
     * @return void
     */
    public function setJsData($jsdata = null) {
        $this->_jsdata = $jsdata;
        return $this;
    }

    /**
     * Get widget's js data
     * @return mixed
     */
    public function getJsData()
    {
        return $this->_jsdata;
    }

    /**
     *
     * @return string
     */
    public function getJsDataJS()
    {
        if (!empty($this->_jsdata)) {
            $jsdata = Helper::toJson(Helper::rmUndef((array)$this->_jsdata));
            $jsdata = "{$this->varInstanceName}.data('php', {$jsdata});";
        }
        // Return
        return trim($jsdata);
    }

    /**
     * @var array Options
     */
    protected $_options = [];

    /**
     * Set options
     * @param array $options An array of options
     * @return void
     */
    public function setOptions(array $options = array())
    {
        $this->_options = array_replace($this->_options, $options);
        return $this;
    }

    /**
     * Get options
     * @return array
     */
    public function getOptions() {
        return $this->_options;
    }

    /**
     * Get widget's jS script
     *
     * @param array $options An array of options
     * @return string
     */
    public function toJS(array $options = array())
    {
        $script = $this->__toString();
        // Wrap init script to function?
        if ($options['wrapper']) {
            $script = <<<JS
// Wrap widget to init function
$('{$this->selector}').data('{$options['wrapper']}', function(){
    return {$script};
});
//.end
JS;
        }
        return $script;
    }
}
