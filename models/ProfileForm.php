<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ProfileForm extends Model
{
    public $status_filt;
    public $criteria_filt;

    public function attributeLabels()
    {
        return [
            'status_filt' => 'Статусы',
            'criteria_filt' => 'Критерии',
        ];
    }

    public function rules()
    {
        return [
            ['status_filt', 'default', 'value' => 0],
            ['criteria_filt', 'default', 'value' => 0],
        ];
    }
}
