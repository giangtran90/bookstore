<?php
class UserController extends Controller
{
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('default/main/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function indexAction(){
        $this->_view->setTitle("My Account");
        $this->_view->render($this->_arrParam['controller'] . '/index');
    }

    public function orderAction(){
        $cart       = Session::get('cart');
        $categoryID = $this->_arrParam['category_id'];
        $bookID     = $this->_arrParam['book_id'];
        $price      = $this->_arrParam['price'];
        if (empty($cart)){
            $cart['quantity'][$bookID]  = 1;
            $cart['price'][$bookID]     = $price;
        } else {
            if (key_exists($bookID, $cart['quantity'])){
                $cart['quantity'][$bookID]  += 1;
                $cart['price'][$bookID]     = $price * $cart['quantity'][$bookID];
            } else {
                $cart['quantity'][$bookID]  = 1;
                $cart['price'][$bookID]     = $price;
            }
        }
        Session::set('cart', $cart);

        $this->item = $this->_model->listItem($this->_arrParam, ['task' => 'book-order']);
        $bookNameURL    = URL::filterURL($this->item['name']) . '-' . $this->item['category_id'] . '-' . $this->item['id'] . '.html';
        $cateNameURL    = URL::filterURL($this->item['category_name']);
        $linkBookHTML   = $cateNameURL . DS . $bookNameURL;

        URL::redirect('default', 'book', 'detail', ['category_id' => $categoryID, 'book_id' => $bookID], $linkBookHTML);
    }

    public function cartAction(){
        $this->_view->setTitle("My Account :: Cart");
        $this->_view->items = $this->_model->listItem($this->_arrParam, ['task' => 'books-in-cart']);
        $this->_view->render($this->_arrParam['controller'] . '/cart');
    }

    public function buyAction(){
        $this->_model->saveItem($this->_arrParam, ['task' => 'submit-cart']);
        URL::redirect('default', 'index', 'index', null, 'home.html');
    }

    public function historyAction(){
        $this->_view->setTitle("My Account :: History");
        $this->_view->items = $this->_model->listItem($this->_arrParam, ['task' => 'books-in-history']);
        $this->_view->render($this->_arrParam['controller'] . '/history');
    }

}

