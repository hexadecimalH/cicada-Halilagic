<?php

namespace Halilagic;


use Twig_Environment;
use Twig_SimpleFunction;
use Twig_Loader_Filesystem;
use Halilagic\Configuration;
use Halilagic\Services\MainService;
use Halilagic\Services\LogInService;
use Halilagic\Services\AdminService;
use Halilagic\Services\ImageStorageService;
use Halilagic\Libraries\ImageManipulationLibrary;

class Application extends \Cicada\Application
{
    public $domain;
    public $protocol;
    public $basePath;

    public function __construct($configPath, $domain, $protocol)
    {
        parent::__construct();
        $this->domain = $domain;
        $this->protocol = $protocol;
        $this->configure("/home/.config/halilagic/");
        $this->basePath = $this['config']->getPathToUpload();
        $this->setupLibraries();
        $this->setupServices($this['config']->getCredentials());
        $this->setupTwig();
        $this->configureDatabase();
        // $this->setupSessionContainer();
    }

    private function setupSessionContainer(){
        $this['user_id'] = null;
    }

    protected function setupLibraries(){
        $this['imageManipualtionLibrary'] = function () {
            return new ImageManipulationLibrary();
        };
    }

    private function setupServices($credentials) {
        $this['logInService'] = function () {
            return new LogInService();
        };

        $this['mainService'] = function () use($credentials) {
            return new MainService($credentials);
        };

        $this['imageStorageService'] = function () {
            return new ImageStorageService($this->domain, $this->protocol, $this->basePath, $this['imageManipualtionLibrary']);
        };

        $this['adminService'] = function () {
            return new AdminService($this['imageStorageService']);
        };


    }

    protected function configure($configPath) {
        $this['config'] = function () use ($configPath) {
            return new Configuration($configPath);
        };
    }

    protected function configureDatabase()
    {
        $dbConfig = $this['config']->getDbConfig();
        \ActiveRecord\Config::initialize(function (\ActiveRecord\Config $cfg) use ($dbConfig) {
            $cfg->set_model_directory('src/Models');
            $cfg->set_connections([
                'main' => sprintf('mysql://%s:%s@%s/%s',
                    $dbConfig['user'], $dbConfig['password'], $dbConfig['host'], $dbConfig['name']
                )
            ]);
            $cfg->set_default_connection('main');
        });
    }
    private function setupTwig() {
        $this['twig'] = function() {
            $loader = new \Twig_Loader_Filesystem('front-end/templates');
            $twig = new  \Twig_Environment($loader, array(
//                'cache' => 'cache',
            ));

            $pathFunction = function ($name, $params = []) {
                /** @var Route $route */
                $route = $this['router']->getRoute($name);
                return $route->getRealPath($params);
            };
            $twig->addFunction(new Twig_SimpleFunction('path', $pathFunction));

            return $twig;
        };
    }
}