<?php
 
    /**
     * Part 1 of initialization: Global Constants for Paths
     *
     */
    define('PUBLIC_WEB_PATH', '/');
    define('INDEX_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')));
    define('APPLICATION_PATH', str_replace('\\', '/', realpath(INDEX_PATH . '/../application')));
    define('LIBRARY_PATH', str_replace('\\', '/', realpath(INDEX_PATH . '/../library')));
    set_include_path(
            LIBRARY_PATH
            . PATH_SEPARATOR
            . get_include_path()
        );
     
    require_once 'Zend/Loader.php';
     
    Zend_Loader::registerAutoload();
     
    /**
     * General Bootstrap
     */
      
    defined('APPLICATION_PATH')
        or define('APPLICATION_PATH', dirname(__FILE__));
         
 
    defined('APPLICATION_ENVIRONMENT')
        or define('APPLICATION_ENVIRONMENT', 'development');
 
    $frontController = Zend_Controller_Front::getInstance();
     
 
    /**
     * Point controllers directory to your app controller directory
     */
    $frontController->setControllerDirectory(APPLICATION_PATH . '/controllers');
     
 
    /**
     * Set environment flag indicator
     */
    $frontController->setParam('env', APPLICATION_ENVIRONMENT);
     
     
    /**
     * Setup the layout
     */
    Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
     
 
    /**
     * Setup layout view
     */
    $view = Zend_Layout::getMvcInstance()->getView();
    $view->doctype('XHTML1_STRICT');
     
 
    /*
     * Setup configurations
     */
     
    $configuration = new Zend_Config_Ini(
        APPLICATION_PATH . '/config/project_config.ini',
        APPLICATION_ENVIRONMENT
    );
     
    $messages = new Zend_Config_Ini(
        APPLICATION_PATH . '/config/project_messages.ini',
        APPLICATION_ENVIRONMENT
    );
                 
    $dbAdapter = Zend_Db::factory($configuration->database);
     
    /**
     * Setup dbAdapter to our tables
     */
    Zend_Db_Table::setDefaultAdapter($dbAdapter);
     
    /**
     * Create cache for Db metadata
     */
    $frontendOptions = array(
                        'lifetime'                  => 25200,
                        'automatic_serialization'   => true
                        );
    $backendOptions  = array(
                         'cache_dir'                => APPLICATION_PATH . '/tmp'
                        );
    $dbCache = Zend_Cache::factory(
                        'Core',
                        'File',
                        $frontendOptions,
                        $backendOptions
                        );
    /**
     * Cache table metadata
     */
    Zend_Db_Table_Abstract::setDefaultMetadataCache($dbCache);
     
    /**
     * General caching
     */
    $frontendOptions = array(
                        'lifetime'                  => 3600,
                        'automatic_serialization'   => true
                        );
    $backendOptions  = array(
                         'cache_dir'                => APPLICATION_PATH . '/tmp'
                        );
    $cache = Zend_Cache::factory(
                        'Core',
                        'File',
                        $frontendOptions,
                        $backendOptions
                        ); 
     
    /**
     * Plugin Loader caching
     */
    $classFileIncCache = APPLICATION_PATH .  '/data/pluginLoaderCache.php';
    if (file_exists($classFileIncCache))
    {
        include_once $classFileIncCache;
    }
    Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
     
    /**
     * Save objects to registry
     */
    $registry = Zend_Registry::getInstance();
    $registry->set('configuration', $configuration);
    $registry->set('dbAdapter', $dbAdapter);
    $registry->set('messages', $messages);
    $registry->set('cache', $cache);
     
    header ('Content-type: text/html; charset=utf-8');
    $dbAdapter->query('SET NAMES utf8');
     
    Zend_Locale::setDefault('en_US');
    Zend_Session::start();
     
    /**
     * Setup the Custom Helpers
     */
    Zend_Controller_Action_HelperBroker::addPrefix('Dc_Helper');
     
    /**
     * Cleanup
     */
    unset($frontController, $view, $configuration, $dbAdapter, $registry);
    unset($backendOptions, $cache, $frontendOptions, $messages, $dbCache);
	
?>