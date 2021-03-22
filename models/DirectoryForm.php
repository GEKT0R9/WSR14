<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class DirectoryForm extends Model
{
    public $text;

    public function rules()
    {
        return [
            ['text', 'required'],
        ];
    }
}
