<?php
class Controller
{
    // VIEW OBJECT
    protected $_view; 

    // MODEL OBJECT
    protected $_model; 

    // TEMPLATE OBJECT
    protected $_templateObj; 

    // ARRAY PARAM (GET-POST)
    protected $_arrParam; 

    // PAGINATION
    protected $_pagination = [
                                'totalItemsPerPage' => 3,
                                'pageRange' => 2
                             ];  

    public function __construct($arrParams)
    {
        $this->setModel($arrParams['module'], $arrParams['controller']);
        $this->setTemplate($this);
        $this->setView($arrParams['module']);
        $this->_pagination['currentPage']   = (isset($arrParams['filter_page'])) ? $arrParams['filter_page'] : 1;
        $arrParams['pagination']            = $this->_pagination;
        $this->setParams($arrParams);
        $this->_view->arrParam = $arrParams;
    }

    // SET MODEL
    public function setModel($module, $modelName)
    {
        $modelName = ucfirst($modelName) . 'Model';
        $filePath = MODULE_PATH . $module . DS . 'models' . DS . $modelName . '.php';
        if (file_exists($filePath)){
            require_once $filePath;
            $this->_model = new $modelName();
        }
    }

    // GET MODEL
    public function getModel()
    {  
        return $this->_model;

    }

    // SET VIEW
    public function setView($modelName)
    {
        $this->_view = new View($modelName);
    }

    // GET VIEW
    public function getView()
    {
        return $this->_view;
    }

    // SET TEMPLATE
    public function setTemplate()
    {
        $this->_templateObj = new Template($this);
    }

    // GET TEMPLATE
    public function getTemplate()
    {
        return $this->_templateObj;
    }

    // SET PARAMS
    public function setParams($arrParam)
    {
        $this->_arrParam = $arrParam;
    }

    // GET PARAMS
    public function getParams()
    {
        return $this->_arrParam;
    }

    // SET PAGINATION
    public function setPagination($config)
    {
        $this->_pagination['totalItemsPerPage'] = $config['totalItemsPerPage'];
        $this->_pagination['pageRange']         = $config['pageRange'];
        $this->_arrParam['pagination']          = $this->_pagination;
        $this->_view->arrParam                  = $this->_arrParam;
    }
}
