<?php 

namespace App\Classes;

use App\Database\DB;

class Validate
{
    private $_passed = false;
    private $_errors = [];
    private $_db;

    public function __construct()
    {
        $this->_db = new DB;
    }

    public function validation($source = [], $items = [])
    {
        if (!empty($source)) {
            foreach ($items as $item => $rules) {
                foreach ($rules as $rule => $ruleValue) {
                    $value = trim($source[$item]);
                    $item = escape($item);
                    $customNameForShowInputsError = $rules['name'] ?? '';
                    if ($rule === 'required' && empty($value)) {
                        $this->addError("لطفا فیلد <strong>{$customNameForShowInputsError}</strong> را پر کنید.");
                    } elseif (!empty($value)) {
                        switch ($rule) {
                            case 'min':
                                if (strlen($value) < $ruleValue) {
                                    $this->addError("<strong>{$customNameForShowInputsError}</strong> وارد شده باید بیشتر از {$ruleValue} کاراکتر باشد.");
                                }
                                break;
                            case 'max':
                                if (strlen($value) > $ruleValue) {
                                    $this->addError("<strong>{$customNameForShowInputsError}</strong> وارد شده باید کمتر از {$ruleValue}  کاراکتر باشد.");
                                }
                                break;
                            case 'matches':
                                if ($value !== $source[$ruleValue]) {
                                    $this->addError("تکرار <strong>{$customNameForShowInputsError}</strong> باید با {$customNameForShowInputsError} برابر باشد.");
                                }
                                break;
                            case 'unique':
                                $check = $this->_db->find($ruleValue, [$item, '=', $value]);
                                if ($check->count()) {
                                        $this->addError("<strong>{$customNameForShowInputsError}</strong> وارد شده قبلا بوده.");
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
            if (empty($this->_errors)) {
                $this->_passed = true;
                return $this;
            }
        }
        return $this;
    }

    public function addError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}

$posts = [
    'input-1' => 'value1',
    // 'input-2' => 'value2',
    // 'input-3' => 'value3',
];

$validate = new Validate;
$validate->validation($posts, [
    'input-1' => [
        'required',
        'min' => '6',
        'name' => 'نام',
    ],
]);