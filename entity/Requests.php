<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица заявок
 * @property int id идентификатор
 * @property string title Заголовок
 * @property string description Описание
 * @property int criterion_id идентификатор критерия
 * @property int before_img_id идентификатор фото до
 * @property int after_img_id идентификатор фото после
 * @property string date дата создания
 * @property int status_id идентификатор статуса
 * @property int create_user_id идентификатор пользователя
 * @package app\entity
 */
class Requests extends ActiveRecord
{

}