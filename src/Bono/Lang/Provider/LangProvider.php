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
     * Default options
     *
     * @var array
     */
    protected $options = array(
        'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
        'lang'   => 'en',
        'debug'  => false,
    );

    /**
     * Initialize the provider
     *
     * @return void
     */
    public function initialize()
    {
        $app = $this->app;

        $Driver = $this->options['driver'];
        $driver = new $Driver($this->options);

        if (is_callable($this->options['lang'])) {
            $fn = $this->options['lang'];
            $this->options['lang'] = $fn();
        }

        $Translator = new Translator($this->options, $driver);

        $app->container->singleton('translator', function () use ($Translator) {
            return $Translator;
        });
    }
}
