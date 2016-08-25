<?php

namespace Temple\Engine;


use Temple\Engine\Exception\ExceptionHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\BaseLanguage;
use Temple\Engine\Languages\LanguageConfig;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends Injection
{

    /** @var bool $shutdownCallbackRegistered */
    private $shutdownCallbackRegistered = false;

    /** @var  EngineWrapper $EngineWrapper */
    private $EngineWrapper;

    /** @var null $subfolder */
    private $subfolder = null;

    /** @var bool $errorHandler */
    private $errorHandler = true;

    /** @var ExceptionHandler $errorHandlerInstance */
    private $errorHandlerInstance;

    /** @var string $cacheDir */
    private $cacheDir = "./Cache";

    /** @var bool $cacheEnabled */
    private $cacheEnabled = true;

    /** @var bool $CacheInvalidation */
    private $CacheInvalidation = true;

    /** @var bool $variableCacheEnabled */
    private $variableCacheEnabled = true;

    /** @var array $templateDirs */
    private $templateDirs = array();

    /** @var bool $showBlockComments */
    private $showBlockComments = true;

    /** @var string $extension */
    private $extension = "tmpl";

    /** @var array $languages */
    private $languages = array();

    /** @var array $defaultLanguage */
    private $defaultLanguage = "./Languages/Html";

    /** @var array $languages */
    private $languageTagName = "lang";

    /** @var array $languageCacheFolders */
    private $languageCacheFolders = array();

    /** @var array $languageConfigs */
    protected $languageConfigs = array();

    /** @var array $curlUrls */
    private $curlUrls = array();

    /** @var bool $useCoreLanguage */
    private $useCoreLanguage = true;

    /** @var array $processedTemplates */
    private $processedTemplates = array();


    /**
     * @param EngineWrapper $EngineWrapper
     */
    public function setEngineWrapper($EngineWrapper)
    {
        $this->EngineWrapper = $EngineWrapper;
    }


    /**
     * updates the config
     */
    public function update()
    {

        if (!$this->shutdownCallbackRegistered) {
            register_shutdown_function(function (Config $configInstance) {
                // todo: if is modified
                $config = array(
                    "cacheDir"             => $configInstance->EngineWrapper->DirectoryHandler()->getCacheDir(),
                    "languageCacheFolders" => $configInstance->getLanguageCacheFolders(),
                    "curlUrls"             => $configInstance->getCurlUrls(),
                    "subfolder"            => $configInstance->getSubfolder(),
                    "cacheEnabled"         => $configInstance->isCacheEnabled(),
                    "templateDirs"         => $configInstance->getTemplateDirs(),
                    "processedTemplates"   => $configInstance->getProcessedTemplates(),
                    "defaultLanguage"      => $configInstance->getDefaultLanguage(),
                    "useCoreLanguage"      => $configInstance->isUseCoreLanguage(),
                    "DocumentRoot"         => $_SERVER["DOCUMENT_ROOT"],
                    "languageConfigs"      => array()
                );

                $languageConfigs = $configInstance->getLanguageConfigs();

                /* @var LanguageConfig $languageConfig */
                foreach ($languageConfigs as $name => $languageConfig) {
                    $config["languageConfigs"][ $name ] = $languageConfig->toArray();
                }

                // todo: update the config instead of adding it
                $configInstance->EngineWrapper->ConfigCache()->save($configInstance);
            }, $this);
            $this->shutdownCallbackRegistered = true;
        }


        if ($this->errorHandler) {
            $this->errorHandlerInstance = new ExceptionHandler();
        } else {
            $this->errorHandlerInstance = null;
        }
    }


    /**
     * @return null
     */
    public function getSubfolder()
    {
        return $this->subfolder;
    }


    /**
     * @param $subfolder
     *
     * @return null
     */
    public function setSubfolder($subfolder)
    {
        $this->subfolder = $subfolder;
        $this->update();

        return $this->subfolder;
    }


    /**
     * @return boolean
     */
    public function isErrorHandler()
    {
        return $this->errorHandler;
    }


    /**
     * @param $errorHandler
     *
     * @return bool
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
        $this->update();

        return $this->errorHandler;
    }


    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }


    /**
     * @param $cacheDir
     *
     * @return string
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
        $this->update();

        return $this->cacheDir;
    }


    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }


    /**
     * @param $cacheEnabled
     *
     * @return bool
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;
        $this->update();

        return $this->cacheEnabled;
    }


    /**
     * @return boolean
     */
    public function isCacheInvalidation()
    {
        return $this->CacheInvalidation;
    }


    /**
     * @param boolean $CacheInvalidation
     */
    public function setCacheInvalidation($CacheInvalidation)
    {
        $this->CacheInvalidation = $CacheInvalidation;
    }


    /**
     * @return boolean
     */
    public function isVariableCacheEnabled()
    {
        return $this->variableCacheEnabled;
    }


    /**
     * @param boolean $variableCacheEnabled
     */
    public function setVariableCacheEnabled($variableCacheEnabled)
    {
        $this->variableCacheEnabled = $variableCacheEnabled;
    }


    /**
     * @return array
     */
    public function getTemplateDirs()
    {
        return $this->templateDirs;
    }


    /**
     * @param $templateDirs
     *
     * @return array
     */
    public function setTemplateDirs($templateDirs)
    {
        $this->templateDirs = $templateDirs;
        $this->update();

        return $this->templateDirs;
    }


    /**
     * @return boolean
     */
    public function isShowBlockComments()
    {
        return $this->showBlockComments;
    }


    /**
     * @param $showBlockComments
     *
     * @return mixed
     */
    public function setShowBlockComments($showBlockComments)
    {
        $this->showBlockComments = $showBlockComments;
        $this->update();

        return $this->showBlockComments;
    }


    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }


    /**
     * @param $extension
     *
     * @return bool
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        $this->update();

        return $this->extension;
    }


    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }


    /**
     * @param $language
     *
     * @return BaseLanguage
     */
    public function getLanguage($language)
    {
        return $this->languages[$language];
    }


    /**
     * @param string $language
     * @param string $key
     */
    public function addLanguage($language, $key = null)
    {
        if (is_null($key)) {
            $key = explode("/", preg_replace("/\/$/", "", $language));
            $key = strtolower(end($key));
        }
        if (!in_array($language, $this->languages)) {
            $language = $this->EngineWrapper->DirectoryHandler()->getPath($language);
            $this->EngineWrapper->Languages()->initLanguageConfig($key,$language);
            $this->languages[$key] = $language;
        }
    }


    /**
     * @return array
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }


    /**
     * @param array $language
     */
    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;
    }


    /**
     * @return array
     */
    public function getLanguageTagName()
    {
        return $this->languageTagName;
    }


    /**
     * @param array $languageTagName
     */
    public function setLanguageTagName($languageTagName)
    {
        $this->languageTagName = $languageTagName;
    }


    /**
     * @return bool
     */
    public function isUseCoreLanguage()
    {
        return $this->useCoreLanguage;
    }


    /**
     * @param bool $useCoreLanguage
     */
    public function setUseCoreLanguage($useCoreLanguage)
    {
        $this->useCoreLanguage = $useCoreLanguage;
    }


    /**
     * @param string $languageCacheFolder
     */
    public function addLanguageCacheFolder($languageCacheFolder)
    {
        if (!isset($this->languageCacheFolders[ $languageCacheFolder ])) {
            $this->languageCacheFolders[ $languageCacheFolder ] = true;
        }

    }


    /**
     * @return array
     */
    public function getLanguageCacheFolders()
    {
        return array_keys($this->languageCacheFolders);
    }


    /**
     * @param LanguageConfig $LanguageConfig
     */
    public function addLanguageConfig($LanguageConfig)
    {

        $key                           = $LanguageConfig->getName();
        $this->languageConfigs[ $key ] = $LanguageConfig;

    }


    /**
     * @param string $language
     *
     * @return LanguageConfig
     */
    public function getLanguageConfig($language)
    {
        return $this->languageConfigs[ $language ];
    }


    /**
     * @return array
     */
    public function getLanguageConfigs()
    {

        return $this->languageConfigs;
    }


    /**
     * @param string|array $curlUrl
     */
    public function addCurlUrl($curlUrl)
    {
        if (is_array($curlUrl)) {
            foreach ($curlUrl as $url) {
                if (!isset($this->curlUrls[ $url ])) {
                    $this->curlUrls[ $url ] = true;
                }
            }
        } else {
            if (!isset($this->curlUrls[ $curlUrl ])) {
                $this->curlUrls[ $curlUrl ] = true;
            }
        }
    }


    /**
     * @return array
     */
    public function getCurlUrls()
    {
        return array_keys($this->curlUrls);
    }


    /**
     * @return array
     */
    public function getProcessedTemplates()
    {
        return $this->processedTemplates;
    }


    /**
     * @param array $processedTemplate
     */
    public function addProcessedTemplate($processedTemplate)
    {
        $this->processedTemplates[] = $processedTemplate;
    }


}