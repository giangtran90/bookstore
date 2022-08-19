<?php
class Validate
{

    // Error array ->  neu co loi thi luu vao day
    private $errors = [];

    // Source array -> gia tri nhap vao
    private $source = [];

    // Rules array -> quy tac nhap
    private $rules  = [];

    // Result array -> neu ko co loi thi luu vao day
    private $result = [];

    // Constructor
    public function __construct($source)
    {
        //if (array_key_exists('submit', $source)) unset($source['submit']);
        $this->source = $source;
    }

    // Add rules
    public function addRules($rules)
    {
        $this->rules = array_merge($rules, $this->rules);
    }

    // Get error
    public function getError()
    {
        return $this->errors;
    }

    // Set error
    public function setError($element, $message)
    {
        $strElement = str_replace('_', ' ', $element);
        if(array_key_exists($element, $this->errors)){
			$this->errors[$element] .= ' - ' . $message;
		}else{
			$this->errors[$element] = '<b>' . ucwords($strElement) . ':</b> ' . $message;
		}
    }

    // Get result
    public function getResult()
    {
        return $this->result;
    }

    // Add rule
    public function addRule($element, $type, $options = null, $required = true)
    {
        $this->rules[$element] = ['type' => $type, '$options' => $options, 'required' => $required];
        return $this;
    }

    // Run
    public function run()
    {
        foreach ($this->rules as $element => $value) {
            if ($value['required'] == true && trim($this->source[$element]) == null) {
                $this->setError($element, 'giá trị này không được rỗng');
            } else {
                switch ($value['type']) {
                    case 'int':
                        $this->validateInteger($element, $value['$options']['min'], $value['$options']['max']);
                        break;
                    case 'string':
                        $this->validateString($element, $value['$options']['min'], $value['$options']['max']);
                        break;
                    case 'existRecord':
                        $this->validateExistRecord($element, $value['$options']);
                        break;
                    case 'notExistRecord':
                        $this->validateNotExistRecord($element, $value['$options']);
                        break;
                    case 'string-notExistRecord':
                        $this->validateString($element, $value['$options']['min'], $value['$options']['max']);
                        $this->validateNotExistRecord($element, $value['$options']);
                        break;
                    case 'email-notExistRecord':
                        $this->validateEmail($element);
                        $this->validateNotExistRecord($element, $value['$options']);
                        break;
                    case 'url':
                        $this->validateURL($element);
                        break;
                    case 'password':
                        $this->validatePassword($element, $value['$options']);
                        break;
                    case 'email':
                        $this->validateEmail($element);
                        break;
                    case 'date':
                        // gia tri thoi gian nen phai kiem tra xem no nam giua khoang thoi gian nao
                        $this->validateDate($element, $value['$options']['start'], $value['$options']['end']);
                        break;
                    case 'group':
                        $this->validateGroupID($element);
                        break;
                    case 'status':
                        $this->validateStatus($element, $value['$options']);
                        break;
                    case 'selectStatus':
                        $this->validateSelectStatus($element);
                        break;
                    case 'file':
                        $this->validateFile($element, $value['$options']['min'], $value['$options']['max'], $value['$options']['extension']);
                        break;
                }
            }
            if (!array_key_exists($element, $this->errors)) {
                $this->result[$element] = $this->source[$element];
            }
        }
        $eleNotValidate = array_diff_key($this->source, $this->errors);
        $this->result   = array_merge($this->result, $eleNotValidate);
    }

    // Validate integer
    public function validateInteger($element, $min = 0, $max = 0)
    {
        if (!filter_var($this->source[$element], FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max)))) {
            $this->setError($element, "'" . $this->source[$element] . "' is an invalid number");
        }
    }

    // Validate string
    public function validateString($element, $min = 0, $max = 0)
    {
        $length = strlen($this->source[$element]);
        if ($length < $min) {
            $this->setError($element, "'" . $this->source[$element] . "' is too short");
        } elseif ($length > $max) {
            $this->setError($element, "'" . $this->source[$element] . "' is too long");
        } elseif (!is_string($this->source[$element])) {
            $this->setError($element, "'" . $this->source[$element] . "' is an invalid string");
        }
    }

    // Validate url
    public function validateURL($element)
    {
        if (!filter_var($this->source[$element], FILTER_VALIDATE_URL)) {
            $this->setError($element, "'" . $this->source[$element] . "' is an invalid url");
        }
    }

    // Validate url
    public function validateEmail($element)
    {
        if (!filter_var($this->source[$element], FILTER_VALIDATE_EMAIL)) {
            $this->setError($element, "'" . $this->source[$element] . "' is an invalid email");
        }
    }

    // Show errors
    public function showError()
    {
        $xhtml = '';
        if (!empty($this->errors)) {
            $xhtml .= '<dl id="system-message"><dt class="error">Error</dt><dd class="error message"><ul>';
            foreach ($this->errors as $key => $value) {
                $xhtml .= '<li>' . $value . '</li>';
            }
            $xhtml .= '</ul></dd></dl>';
        }
        return $xhtml;
    }

    // Show errors public
    public function showErrorPublic()
    {
        $xhtml = '';
        if (!empty($this->errors)) {
            $xhtml .= '<ul class="error-public">';
            foreach ($this->errors as $key => $value) {
                $xhtml .= '<li>' . $value . '</li>';
            }
            $xhtml .= '</ul>';
        }
        return $xhtml;
    }

    // Is valid
    public function isValid()
    {
        if (count($this->errors) > 0) return false;
        return true;
    }

    // Validate GroupID
    public function validateGroupID($element)
    {
        if ($this->source[$element] == 0) {
            $this->setError($element, "Select group");
        }
    }

    // Validate status
    public function validateStatus($element, $options)
    {
        if (in_array($this->source[$element], $options['deny']) == true) {
            $this->setError($element, "Vui lòng chọn giá trị khác giá trị mặc định");
        }
    }

    // Validate status select
    public function validateSelectStatus($element)
    {
        $check = $this->source[$element];
        if ($check == 'default') {
            $this->setError($element, "Select status");
        }
    }

    // Validate password - 8 numbers
    public function validatePassword($element, $options)
    {
        if($options['action'] == 'add' || ($options['action'] == 'edit' && $this->source[$element])){
            $pattern = '#^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,8}$#'; // Php4567@
            if (!preg_match($pattern, $this->source[$element])) {
                $this->setError($element, ' is an invalid password');
            };
        }
    }

    // Validate date
    public function validateDate($element, $start, $end)
    {
        // Start
        $arrDateStart     = date_parse_from_format('d/m/Y', $start);
        $tsStart        = mktime(0, 0, 0, $arrDateStart['month'], $arrDateStart['day'], $arrDateStart['year']);

        // End
        $arrDateEnd = date_parse_from_format('d/m/Y', $end);
        $tsEnd        = mktime(0, 0, 0, $arrDateEnd['month'], $arrDateEnd['day'], $arrDateEnd['year']);

        // Current
        $arrDateCurrent = date_parse_from_format('d/m/Y', $this->source[$element]);
        $tsCurrent        = mktime(0, 0, 0, $arrDateCurrent['month'], $arrDateCurrent['day'], $arrDateCurrent['year']);

        if ($tsCurrent < $tsStart || $tsCurrent > $tsEnd){
            $this->setError($element, "'" . $this->source[$element] . "' is an invalid date");
        }
    }

    // Validate exist Record
    public function validateExistRecord($element, $options)
    {
        $database   = $options['database'];
        $query      = $options['query'];
       if ($database->isExist($query) == false) {
           $this->setError($element, "'" . $this->source[$element] . "' is not exist");
       }
    }

    // Validate not exist Record
    public function validateNotExistRecord($element, $options)
    {
        $database   = $options['database'];
        $query      = $options['query'];
       if ($database->isExist($query) == true) {
           $this->setError($element, "'" . $this->source[$element] . "' giá trị này đã tồn tại!");
       }
    }

    // Validate File
	private function validateFile($element, $min, $max, $extension){
		if($this->source[$element]['name'] != null){
			if(!filter_var($this->source[$element]['size'], FILTER_VALIDATE_INT, array("options"=>array("min_range"=>$min,"max_range"=>$max)))){
				$this->setError($element, 'kích thước không phù hợp');
			}
				
			$ext = pathinfo($this->source[$element]['name'], PATHINFO_EXTENSION);
			if(in_array($ext, $extension) == false){
				$this->setError($element, 'phần mở rộng không phù hợp');
			}
		}
	}
}
