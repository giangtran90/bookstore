<?php
class UserController extends Controller
{
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('admin/main/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    // LIST USER
    public function indexAction()
    {
        $this->_view->_title        = "User Manager :: List";
        $totalItems                 = $this->_model->countItem($this->_arrParam);
        $configPagination           = ['totalItemsPerPage' => 5, 'pageRange' => 2];
        $this->setPagination($configPagination);
        $this->_view->pagination    = new Pagination($totalItems, $this->_pagination);
        $this->_view->slbGroup      = $this->_model->itemInSelectbox($this->_arrParam, null);
        $this->_view->Items         = $this->_model->listItem($this->_arrParam, null);
        $this->_view->render('user/index');
    }
    // ADD - EDIT USER
    public function formAction()
    {
        $this->_view->_title        = "User :: Add";
        $this->_view->slbGroup      = $this->_model->itemInSelectbox($this->_arrParam, null);
        if (isset($this->_arrParam['id'])) {
            $this->_view->_title        = "User :: Edit";
            $this->_arrParam['form'] = $this->_model->infoItem($this->_arrParam);
            if (empty($this->_arrParam['form'])) URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
        }
        if (@$this->_arrParam['form']['token'] > 0) {
            $task = 'add';
            $queryUsername  = "SELECT `id` FROM `" . TBL_USER . "` WHERE `username` = '" . $this->_arrParam['form']['username'] . "'";
            $queryEmail     = "SELECT `id` FROM `" . TBL_USER . "` WHERE `email` = '" . $this->_arrParam['form']['email'] . "'";
            $requirePass   = true;
            if (isset($this->_arrParam['form']['id'])){
                $task            = 'edit';
                $requirePass    = false;
                $queryUsername  .= " AND `id` <> '".$this->_arrParam['form']['id']."'";
                $queryEmail     .= " AND `id` <> '".$this->_arrParam['form']['id']."'";
            }
            $validate = new Validate($this->_arrParam['form']);
            $validate->addRule('username', 'string-notExistRecord', ['database' => $this->_model, 'query' => $queryUsername, 'min' => 3, 'max' => 255])
                    ->addRule('ordering', 'int', ['min' => 1, 'max' => 99])
                    ->addRule('email', 'email-notExistRecord', ['database' => $this->_model, 'query' => $queryEmail])
                    ->addRule('password', 'password', ['action' => $task], $requirePass)
                    ->addRule('status', 'status', ['deny' => ['default']])
                    ->addRule('group_id', 'status', ['deny' => ['default']]);
            $validate->run();
            $this->_arrParam['form'] = $validate->getResult();
            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showError();
            } else {
                $id = $this->_model->saveItem($this->_arrParam, ['task' => $task]);
                if ($this->_arrParam['type'] == 'save-close') URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
                if ($this->_arrParam['type'] == 'save-new') URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'form');
                if ($this->_arrParam['type'] == 'save') URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'form', ['id' => $id]);
            }
        }
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render('user/form');
    }
    // Action : ajax Status (*)
    public function ajaxStatusAction()
    {
        $result = $this->_model->changeStatus($this->_arrParam, array('task' => 'change-ajax-status'));
        echo json_encode($result);
    }
    // Action : Status (*)
    public function statusAction()
    {
        $this->_model->changeStatus($this->_arrParam, array('task' => 'change-status'));
        URL::redirect('admin', 'user', 'index');
    }
    // Action : trash (*)
    public function trashAction()
    {
        $this->_model->deleteItems($this->_arrParam);
        URL::redirect('admin', 'user', 'index');
    }
    // Action : ordering (*)
    public function orderingAction()
    {
        $this->_model->ordering($this->_arrParam);
        URL::redirect('admin', 'user', 'index');
    }
}
