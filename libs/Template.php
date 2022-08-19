<?php
class Template
{
    // File Config admin\main\template.ini
    private $_fileConfig;

    // File Template admin\main\index.php
    private $_fileTemplate;

    // File Template admin\main\
    private $_folderTemplate;

    // Controller Object
    private $_controller;

    public function __construct($controller)
    {
        $this->_controller = $controller;
    }

    public function load()
    {
        $fileConfig         = $this->getFileConfig();
        $fileTemplate       = $this->getFileTemplate();
        $folderTemplate     = $this->getFolderTemplate();

        $pathFileConfig = TEMPLATE_PATH . $folderTemplate . $fileConfig;
        if (file_exists($pathFileConfig)) {
            $arrConfig = parse_ini_file($pathFileConfig);
            $view = $this->_controller->getView();
            $view->_title           = $view->createTitle($arrConfig['title']);
            $view->_metaHTTP        = $view->createMeta($arrConfig['metaHTTP'], 'http-equiv');
            $view->_metaName        = $view->createMeta($arrConfig['metaName'], 'name');
            $view->_cssFiles        = $view->createLink($this->_folderTemplate . $arrConfig['dirCss'], $arrConfig['fileCss'], 'css');
            $view->_jsFiles         = $view->createLink($this->_folderTemplate . $arrConfig['dirJs'], $arrConfig['fileJs'], 'js');
            $view->_dirImg          = TEMPLATE_URL.$this->_folderTemplate . $arrConfig['dirImg'];

            $view->_templatePath    = TEMPLATE_PATH . $folderTemplate . $fileTemplate;
        }
    }

    // Set file template ('index.php')
    public function setFileTemplate($value = 'index.php')
    {
        $this->_fileTemplate = $value;
    }

    // get file template
    public function getFileTemplate()
    {
        return $this->_fileTemplate;
    }

    // Set file config ('template.ini')
    public function setFileConfig($value = 'template.ini')
    {
        $this->_fileConfig = $value;
    }

    // Get file config
    public function getFileConfig()
    {
        return $this->_fileConfig;
    }

    // Set folder template ('admin/main/')
    public function setFolderTemplate($value = 'admin/main/')
    {
        $this->_folderTemplate = $value;
    }

    // Get folder template
    public function getFolderTemplate()
    {
        return $this->_folderTemplate;
    }
}