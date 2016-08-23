<?php

namespace Sample\Model;

use \Sample\Model\BaseModel;
use \Carbon\Carbon;


class ReminderModel extends BaseModel {

	protected $guarded = [];
	protected $mutators = ['remind_at' => "carbonize"];

	public $id = null;
	public $task_id = null;
	public $remind_at = null;
	public $created_at = null;
	public $updated_at = null;
	public $deleted_at = null;


	public static function carbonize($v) {
		return Carbon::parse($v)->toDateTimeString();
	}

}