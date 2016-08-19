<?php

namespace IlGala\LaravelWizzy;

/**
 * Description of Wizzy
 *
 * @author ilgala
 */
class Wizzy
{

    /**
     * Config repository.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $configRepository;

    /**
     * Illuminate router class.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Illuminate request class.
     *
     * @var \Illuminate\Routing\Request
     */
    protected $request;

    /**
     * Illuminate request class.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Application system requirements array.
     *
     * @var array
     */
    private $system_requirements;

    /**
     * Wizzy enabled steps.
     *
     * @var array
     */
    private $steps;

    /**
     * Wizzy route group prefix.
     *
     * @var string
     */
    private $prefix;

    /**
     * Creates new instance.
     */
    public function __construct()
    {
        $this->app = app();

        $this->configRepository = $this->app['config'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];

        $this->system_requirements = $this->configRepository->get('wizzy.system_requirements');
        $this->steps = $this->configRepository->get('wizzy.steps');
        $this->prefix = $this->configRepository->get('wizzy.prefix', '');
    }

    /**
     * Get wizzy route group prefix
     */
    public static function getPrefix()
    {
        $configRepository = app()->app['config'];
        return $configRepository->get('wizzy.prefix', '');
    }

}
