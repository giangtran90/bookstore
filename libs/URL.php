<?php
class URL
{
    public static function createLink($module, $controller, $action, $params = null, $router = null)
    {
        if (!empty($router)) return ROOT_URL . $router;
        $linkParams = '';
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $linkParams .= "&$key=$value";
            }
        }
        $url = ROOT_URL . 'index.php?module=' . $module . '&controller=' . $controller . '&action=' . $action . $linkParams;
        return $url;
    }

    public static function redirect($module, $controller, $action, $params = null, $router = null)
    {
        $link = self::createLink($module, $controller, $action, $params, $router);
        $url = header('Location:' . $link);
        return $url;
    }

    public static function checkRefreshPage($value, $module, $controller, $action, $params = null)
    {
        if (Session::get('token') == $value) {
            Session::delete('token');
            URL::redirect($module, $controller, $action, $params);
        } else {
            Session::set('token', $value);
        }
    }

    private function removeSpaces($value) {
        $value = trim($value);
        $value = preg_replace('#(\s)+#', ' ', $value); // #(\s)+# : \s la space + la 1->n
        return $value;
    }

    private function replaceSpaces($value) {
        $value = trim($value);
        $value = str_replace(' ', '-', $value);
        $value = preg_replace('#(-)+#', '-', $value); // #(\s)+# : \s la space + la 1->n
        return $value;
    }

    private function removeCircumflex($value){
        /*a à ả ã á ạ ă ằ ẳ ẵ ắ ặ â ầ ẩ ẫ ấ ậ b c d đ e è ẻ ẽ é ẹ ê ề ể ễ ế ệ
		 f g h i ì ỉ ĩ í ị j k l m n o ò ỏ õ ó ọ ô ồ ổ ỗ ố ộ ơ ờ ở ỡ ớ ợ
		p q r s t u ù ủ ũ ú ụ ư ừ ử ữ ứ ự v w x y ỳ ỷ ỹ ý ỵ z*/
        $value = strtolower($value);

        $characterA = '#(a|à|ả|ã|á|ạ|ă|ằ|ẳ|ẵ|ắ|ặ|â|ầ|ẩ|ẫ|ấ|ậ|À|Ả|Ã|Ạ|Ă|Ằ|Ắ|Ẵ|Ặ|Â|Ầ|Ấ|Ẫ|Ẩ|Ậ)#';
        $replaceA   = 'a';
        $value      = preg_replace($characterA, $replaceA, $value);

        $characterD = '#(đ|Đ)#imsU';
        $replaceD   = 'd';
        $value      = preg_replace($characterD, $replaceD, $value);

        $characterE = '#(e|è|ẻ|ẽ|é|ẹ|ê|ề|ể|ễ|ế|ệ|E|È|Ẻ|Ẽ|É|Ẹ|Ê|Ề|Ể|Ễ|Ế|Ệ)#';
        $replaceE   = 'e';
        $value      = preg_replace($characterE, $replaceE, $value);

        $characterI = '#(i|ì|ỉ|ĩ|í|ị)#';
        $replaceI   = 'i';
        $value      = preg_replace($characterI, $replaceI, $value);

        $characterO = '#(o|ò|ỏ|õ|ó|ọ|ô|ồ|ổ|ỗ|ố|ộ|ơ|ờ|ở|ỡ|ớ|ợ|O|Ò|Ó|Ỏ|Õ|Ọ|Ô|Ồ|Ố|Ổ|Ỗ|Ơ|Ớ|Ở|Ờ|Ỡ)#imsU';
        $replaceO   = 'o';
        $value      = preg_replace($characterO, $replaceO, $value);

        $characterU = '#(u|ù|ủ|ũ|ú|ụ|ư|ừ|ử|ữ|ứ|ự|U|Ù|Ú|Ủ|Ũ|Ụ|Ư|Ừ|Ứ|Ử|Ữ|Ự)#imsU';
        $replaceU   = 'u';
        $value      = preg_replace($characterU, $replaceU, $value);

        $characterY = '#(y|ỳ|ỷ|ỹ|ý|ỵ)#imsU';
        $replaceY   = 'y';
        $value      = preg_replace($characterY, $replaceY, $value);

        $characterSpecial = '#(,|$)#imsU';
        $replaceSpecial   = '';
        $value      = preg_replace($characterSpecial, $replaceSpecial, $value);

        return $value;
    }

    public static function filterURL($value){
        $value  = (new URL)->replaceSpaces($value);
        $value  = (new URL)->removeCircumflex($value);
        return $value;
    }
}
