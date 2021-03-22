<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ProfileForm extends Model
{
    public $filt;

    public function rules()
    {
        return [
            ['filt', 'default', 'value' => 0],
        ];
    }
}
