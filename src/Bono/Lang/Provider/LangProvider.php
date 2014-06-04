<?php namespace Bono\Lang\Provider;

use Bono\Provider\Provider;
use Bono\App;
use Bono\Lang\Translator;
use Bono\Lang\Driver\FileDriver;

/**
 * Provide language package to Bono container
 */
class LangProvider extends Provider
{
    /**
     * Application context
     *
     * @var \Bono\App
     */
    protected $app = null;

    /**
     * Configuration
     *
     * @var array
     */
    protected $config = array();

    /**
     * Initialize the provider
     *
     * @return void
     */
    public function initialize()
    {
        $app = $this->app;

        $defaultConfig = array(
            'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
            'lang' => 'en',
            'debug' => true
        );
        $config = array_merge($defaultConfig, $app->config('lang') ?: array());
        $Driver = $config['driver'];
        $driver = new $Driver($config);

        $app->config('lang', $config);

        $this->config = $app->config('lang');

        $Translator = new Translator($this->config, $driver);

        $app->container->singleton('translator', function() use ($Translator) {
            return $Translator;
        });
    }
}
