<?php

namespace Sample\Model;

use \Sample\Model\BaseModel;

class TodoModel extends BaseModel {

	protected $guarded = [];

	public $id = null;
	public $note = null;
	public $created_at = null;
	public $updated_at = null;
	public $deleted_at = null;

}