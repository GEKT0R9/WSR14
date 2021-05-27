<?php


namespace app\models;


use yii\base\Model;
use yii\validators\FileValidator;

class AcceptRequestForm extends Model
{
    public $id;
    public $image;
    public $comment;

    public function attributeLabels()
    {
        return [
            'image' => 'Загрузить фотографию',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['image','id'], 'required'],
            ['image', 'validateImage'],
            ['comment', 'default'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateImage($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (empty($this->image)) {
                $this->addError($attribute, 'Вы не выбрали файл');
            }
            if ($this->image->size > 10485760) {
                $this->addError($attribute, 'Вы выбрали слишком большой файл');
            }
            if (
                $this->image->type != 'image/bmp'
                && $this->image->type != 'image/png'
                && $this->image->type != 'image/jpeg'
            ) {
                $this->addError($attribute, 'Формат файла не подходит. Только png, jpg, jpeg, bmp');
            }
        }
    }
}