<?php
class BookController extends Controller
{
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('default/main/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    // LIST BOOK
    public function listAction()
    {
        $this->_view->setTitle("Book :: List");
        $this->_view->category_name = $this->_model->infoItem($this->_arrParam, ['task' => 'get-cat-name']);
        $this->_view->Items         = $this->_model->listItem($this->_arrParam, ['task' => 'books-in-cat']);
        $this->_view->render($this->_arrParam['controller'] . '/list');
    }
    // DETAIL BOOK
    public function detailAction()
    {
        $this->_view->setTitle("Book :: Detail");
        $this->_view->bookDetail  = $this->_model->infoItem($this->_arrParam, ['task' => 'book-detail']);
        $this->_view->bookRelated = $this->_model->listItem($this->_arrParam, ['task' => 'books-relate']);
        $this->_view->render($this->_arrParam['controller'] . '/detail');
    }
}
