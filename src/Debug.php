<?php

namespace Rseon\Mallow;

use DebugBar\StandardDebugBar;

class Debug
{
    protected $debugbar;

    protected static $instance;
    public static function getInstance() {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Get if DebugBar is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return getenv('APP_DEBUG') === '1';
    }

    /**
     * Add collector to DebugBar
     *
     * @param $collector
     * @return $this
     * @throws \DebugBar\DebugBarException
     */
    public function addCollector($collector)
    {
        if($this->isEnabled()) {
            $this->debugbar->addCollector($collector);
        }
        return $this;
    }

    /**
     * Add multiple collectors to DebugBar
     *
     * @param array $collectors
     * @return $this
     * @throws \DebugBar\DebugBarException
     */
    public function addCollectors(array $collectors)
    {
        foreach($collectors as $collector) {
            $this->addCollector($collector);
        }
        return $this;
    }

    /**
     * Get collector from DebugBar
     *
     * @param $collector
     * @return \DebugBar\DataCollector\DataCollectorInterface
     * @throws \DebugBar\DebugBarException
     */
    public function getCollector($collector)
    {
        if($this->isEnabled()) {
            return $this->debugbar->getCollector($collector);
        }
    }

    /**
     * Get DebugBar
     *
     * @return StandardDebugBar
     */
    public function getDebugbar()
    {
        return $this->debugbar;
    }

    /**
     * Return the javascript to render in the head
     *
     * @return string
     */
    public function renderHead()
    {
        if($this->isEnabled()) {
            return $this->debugbar->getJavascriptRenderer()->renderHead();
        }
    }

    /**
     * Return the javascript to render in the foot and stop app measure
     *
     * @return string
     */
    public function render()
    {
        if($this->isEnabled()) {
            $this->debugbar['time']->stopMeasure('App');
            return $this->debugbar->getJavascriptRenderer()->render();
        }
    }

    /**
     * Set DebugBar and start app measure.
     */
    protected function __construct()
    {
        if($this->isEnabled()) {
            $this->debugbar = new StandardDebugBar;
            $this->debugbar['time']->startMeasure('App', 'Application');
        }
    }
}