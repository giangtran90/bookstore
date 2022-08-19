<?php
class IndexController extends Controller
{
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('default/main/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function noticeAction()
    {
        
        $this->_view->render('index/notice');
    }

    public function indexAction()
    {
        $this->_view->listFeaturedBooks = $this->_model->listItems($this->_arrParam, ['task' => 'books-featured']);
        $this->_view->listNewBooks      = $this->_model->listItems($this->_arrParam, ['task' => 'books-new']);
        $this->_view->render('index/index');
    }

    public function registerAction()
    {
        $this->_view->setTitle("Register");
        $userInfo = Session::get('user');
        if (@$userInfo['login'] == true && @$userInfo['time'] + TIME_LOGIN >= time()){
            URL::redirect('default', 'user', 'index');
        }

        if (isset($this->_arrParam['form']['submit'])) {
            URL::checkRefreshPage($this->_arrParam['form']['token'], 'default', 'index', 'register');

            $queryUsername  = "SELECT `id` FROM `" . TBL_USER . "` WHERE `username` = '" . $this->_arrParam['form']['username'] . "'";
            $queryEmail     = "SELECT `id` FROM `" . TBL_USER . "` WHERE `email` = '" . $this->_arrParam['form']['email'] . "'";

            $validate = new Validate($this->_arrParam['form']);
            $validate->addRule('username', 'string-notExistRecord', ['database' => $this->_model, 'query' => $queryUsername, 'min' => 3, 'max' => 255])
                     ->addRule('email', 'email-notExistRecord', ['database' => $this->_model, 'query' => $queryEmail])
                     ->addRule('password', 'password', ['action' => 'add']);
            $validate->run();
            $this->_arrParam['form'] = $validate->getResult();

            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showErrorPublic();
            } else {
                $id = $this->_model->saveItem($this->_arrParam, ['task' => 'user-register']);
                URL::redirect('default', 'index', 'notice', ['type' => 'register-success']);
            }
        }
        $this->_view->render('index/register');
    }

    public function logoutAction()
    {
        Session::delete('user');
        URL::redirect('default', 'index', 'index', null, 'home.html');
    }

    public function loginAction()
    {
        $userInfo = Session::get('user');
        if (@$userInfo['login'] == true && @$userInfo['time'] + TIME_LOGIN >= time()){
            URL::redirect('default', 'user', 'index', null, 'my-account.html');
        }

        $this->_view->setTitle("Login");
        if (@$this->_arrParam['form']['token'] > 0){
            $validate   = new Validate($this->_arrParam['form']);
            $email      = $this->_arrParam['form']['email'];
            $password   = md5($this->_arrParam['form']['password']);
            $query      = "SELECT `id` FROM `user` WHERE `email` = '$email' AND `password` = '$password'";
            $validate->addRule('email', 'existRecord', ['database' => $this->_model, 'query' => $query]);
            $validate->run();

            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showErrorPublic();
            } else {
                $infoUser       = $this->_model->infoItems($this->_arrParam);
                $arraySession   = [
                                    'login'     => true,
                                    'info'      => $infoUser,
                                    'time'      => time(),
                                    'group_acp' => $infoUser['group_acp']
                                  ];
                Session::set('user', $arraySession);
                URL::redirect('default', 'user', 'index', null, 'my-account.html');

            }
        }
        $this->_view->render('index/login');
    }
}
