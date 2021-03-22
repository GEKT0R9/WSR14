<?php


namespace app\models;


use yii\base\Model;
use yii\validators\FileValidator;

class CreateRequestForm extends Model
{
    public $title;
    public $description;
    public $criterion;
    public $image;

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'description' => 'Описание',
            'criterion' => 'Критерий',
            'image' => 'Фото',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'description',
                    'criterion',
                    'image'
                ],
                'required'
            ],
            ['image', 'validateImage'],
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