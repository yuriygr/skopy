<?php

use \Phalcon\Mvc\Model\Relation;

class Users extends ModelBase
{

	public $id;

	public $login;

	public $password;

	public $name;

	public $rang;

	public function initialize()
	{
		$this->hasMany('id', 'Notes', 'user_id', [
			'alias' => 'notes',
			'foreignKey' => [
				'action' => Relation::ACTION_CASCADE,
			]
		]);
	}

	public function getAvatar($size = '200')
	{
		$grav_url = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=' . $size;
		return $grav_url;
	}

	public function getRole()
	{
		switch ($this->rang) {
			case 'admin':
				return 'Владелец';
				break;

			case 'user':
			default:
				return 'Читатель';
				break;
		}
	}
}
