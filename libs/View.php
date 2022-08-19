<?php
class View
{
    public $_moduleName;
    public $_templatePath;
    public $_title;
    public $_metaHTTP;
    public $_metaName;
    public $_cssFiles;
    public $_jsFiles;
    public $_dirImg;
    public $_fileView;

    public function __construct($_moduleName)
    {
        $this->_moduleName = $_moduleName;
    }

    public function render($fileInclude, $loadFull = true)
    {

        $filePath = MODULE_PATH . $this->_moduleName . DS . 'views' . DS . $fileInclude . '.php';
        if (file_exists($filePath)) {
            if ($loadFull == true) {
                // truyen fileInclude ra ngoai
                $this->_fileView = $fileInclude;
                require_once $this->_templatePath;
            } else {
                require_once $filePath;
            }
        } else {
            echo '<h3>' . __METHOD__ . ' error' . '</h3>';
        }
    }

    // Create Title
    public function createTitle($value)
    {
        return '<title>' . $value . '</title>';
    }

    // Set Title
    public function setTitle($value)
    {
        $this->_title = '<title>' . $value . '</title>';
    }

    // Create Meta (name - http-equiv)
    public function createMeta($arrMeta, $typeMeta = 'name')
    {
        $xhtml = '';
        if (!empty($arrMeta)) {
            foreach ($arrMeta as $meta) {
                $temp       = explode('|', $meta);
                $xhtml   .= '<meta ' . $typeMeta . '="' . $temp[0] . '" content="' . $temp[1] . '" />';
            }
        }

        return $xhtml;
    }

    // Create link (css - js)
    public function createLink($path, $files, $type = 'css')
    {
        $xhtml = '';
        if (!empty($files)) {
            $path = TEMPLATE_URL . $path . DS;
            foreach ($files as $file) {
                if ($type == 'css') {
                    $xhtml   .= '<link rel="stylesheet" type="text/css" href="' . $path .  $file . '"/>';
                } else if ($type == 'js') {
                    $xhtml   .= '<script type="text/javascript" src="' . $path .  $file . '"></script>';
                }
            }
        }

        return $xhtml;
    }

    // Append (CSS-JS) nhung them file css- js
    public function appendLink($arrayfile, $type = 'css')
    {
        if (!empty($arrayfile)) {
            foreach ($arrayfile as $file) {
                $path = APPLICATION_URL . $this->_moduleName . DS . 'views' . DS . $file;
                if ($type == 'css') {
                    $this->_cssFiles .= '<link rel="stylesheet" type="text/css" href="' . $path . '"/>';
                } else if ($type == 'js') {
                    $this->_jsFiles   .= '<script type="text/javascript" src="' . $path . '"></script>';
                }
            }
        }
    }
}
