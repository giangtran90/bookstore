<?php
class Helper
{
    // created button
    public static function cmsButton($name, $id, $link, $icon, $type = 'new')
    {
        $xhtml = '<li class="button" id="' . $id . '">';
        if ($type == 'new') {
            $xhtml .= '<a class="modal" href="' . $link . '"><span class="' . $icon . '"></span>' . $name . '</a>';
        } else if ($type == 'submit') {
            $xhtml .= '<a class="modal" href="#" onclick="javascript:submitForm(\'' . $link . '\');"><span class="' . $icon . '"></span>' . $name . '</a>';
        }
        $xhtml .= '</li>';
        return $xhtml;
    }
    // created icon status
    public static function cmsStatus($statusValue, $link, $id)
    {
        $strStatus = ($statusValue == 0) ? 'unpublish' : 'publish';
        $xhtml    = '<a class="jgrid" id="status-' . $id . '" href="javascript:changeStatus(\'' . $link . '\');">
                        <span class="state ' . $strStatus . '"></span>
                    </a>';
        return $xhtml;
    }
    // created icon GroupACP
    public static function cmsGroupACP($groupACPValue, $link, $id)
    {
        $strGroupACP = ($groupACPValue == 0) ? 'unpublish' : 'publish';
        $xhtml    = '<a class="jgrid" id="group_acp-' . $id . '" href="javascript:changeGroupACP(\'' . $link . '\');">
                        <span class="state ' . $strGroupACP . '"></span>
                    </a>';
        return $xhtml;
    }
    // created icon Special
    public static function cmsSpecial($specialValue, $link, $id)
    {
        $strSpecial = ($specialValue == 0) ? 'unpublish' : 'publish';
        $xhtml    = '<a class="jgrid" id="special-' . $id . '" href="javascript:changeSpecial(\'' . $link . '\');">
                        <span class="state ' . $strSpecial . '"></span>
                    </a>';
        return $xhtml;
    }
    // created title Sort
    public static function cmsLinkSort($name, $column, $columnPost, $orderPost)
    {
        $img    = '';
        $order  = ($orderPost == 'asc') ? 'desc' : 'asc';
        if ($column == $columnPost) {
            $img = '<img src="' . TEMPLATE_URL . 'admin\main\images\admin\sort_' . $orderPost . '.png" alt="">';
        }
        $xhtml = '<a href="#" onclick="javascript:sortList(\'' . $column . '\',\'' . $order . '\')">' . $name . $img . '</a>';
        return $xhtml;
    }
    // created select box
    public static function cmsSelectbox($name, $class, $arrValue, $keySelect = 'default', $style = null)
    {
        $xhtml = '<select style="' . $style . '" name="' . $name . '" class="' . $class . '">';
        foreach ($arrValue as $key => $value) {
            if ($key == $keySelect && is_numeric($keySelect)) {
                $xhtml .= '<option selected="selected" value="' . $key . '">' . $value . '</option>';
            } else {
                $xhtml .= '<option value="' . $key . '">' . $value . '</option>';
            }
        }
        $xhtml .= '</select>';
        return $xhtml;
    }
    // created input
    public static function cmsInput($type, $name, $id, $value, $class = null, $size = null)
    {
        $strSize = ($size == null) ? '' : "size='$size'";
        $xhtml   = sprintf('<input type="%s" name="%s" id="%s" value="%s" class="%s" %s>', $type, $name, $id, $value, $class, $strSize);
        return $xhtml;
    }
    // created row form admin
    public static function cmsRowForm($lblName, $input, $required = false)
    {
        $strRequired = '';
        if ($required == true)  $strRequired = '<span class="star">&nbsp;*</span>';
        $xhtml = sprintf('<li><label>%s%s</label>%s</li>', $lblName, $strRequired, $input);
        return $xhtml;
    }
    // created row form public
    public static function cmsRow($lblName, $input, $submit = false)
    {
        $xhtml = sprintf('<div class="form_row"><label class="contact"><strong>%s:</strong></label>%s</div>', $lblName, $input);
        if ($submit == true) {
            $xhtml = sprintf('<div class="form_row">%s</div>', $input);
        }
        return $xhtml;
    }
    // format date
    public static function formatDate($format, $value)
    {
        $result = '';
        if (!empty($value) && ($value != '0000-00-00')) {
            $result = date($format, strtotime($value));
        }
        return $result;
    }
    // Create Message
    public static function cmsMessage($message)
    {
        $xhtml = '';
        if (!empty($message)) {
            $xhtml = '<dl id="system-message">
							<dt class="' . $message['class'] . '">' . ucfirst($message['class']) . '</dt>
							<dd class="' . $message['class'] . ' message">
								<ul>
									<li>' . $message['content'] . '</li>
								</ul>
							</dd>
						</dl>';
        }
        return $xhtml;
    }
    // Create image
    public static function createImage($folderImage, $image, $attri = null)
    {
        $class          = (!empty($attri['class'])) ? $attri['class'] : '';
        $width          = (!empty($attri['width'])) ? $attri['width'] : '';
        $height         = (!empty($attri['height'])) ? $attri['height'] : '';
        $strAttibute    = 'width=' . $width . ' height=' . $height . ' class="' . $class . '"';
        $picturePath    = UPLOAD_PATH . $folderImage . DS . $image;
        if (file_exists($picturePath) == true) {
            $picture        = '<img ' . $strAttibute . ' src="' . UPLOAD_URL . $folderImage . DS . $image . '">';
        } else {
            $picture        = '<img ' . $strAttibute . ' src="' . UPLOAD_URL . $folderImage . DS . 'default.jpg' . '">';
        }

        return $picture;
    }
}
