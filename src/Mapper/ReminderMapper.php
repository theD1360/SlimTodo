<?php 

namespace Sample\Mapper;

use Sample\Mapper\BaseMapper;
use Sample\Model\ReminderModel;
use Sample\Traits\SingletonMapperTrait;


class ReminderMapper extends BaseMapper{

	use SingletonMapperTrait;

	public function getList($limit = 50)
	{
		$stmt = $this->db->prepare("SELECT * FROM reminder where deleted_at IS NULL order by id desc limit $limit");
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function insert(ReminderModel $reminder)
	{
		$stmt = $this->db->prepare('insert into reminder (task_id, remind_at) values (?, ?)');
		$stmt->execute([$reminder->task_id, $reminder->remind_at]);
		$id = $this->db->lastInsertId();
		$reminder->massAssign($this->getReminderById($id));

		return $reminder;
	}

	public function update(ReminderModel $reminder)
	{
		$stmt = $this->db->prepare('update reminder set remind_at = ? where id =
			?');
		$stmt->execute([$reminder->remind_at, $reminder->id]);

		return $reminder->massAssign($this->getreminderById($reminder->id));
	}

	public function delete(ReminderModel $reminder)
	{
		$stmt = $this->db->prepare('update reminder set deleted_at = now() where id =
			?');
		return $stmt->execute([$reminder->id]);
	}


	public function getReminderById($id)
	{
		$stmt = $this->db->prepare('SELECT * FROM reminder where id = ? limit 1');
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}


	public function getRemindersByTodoId($id, $limit = 50)
	{
		$stmt = $this->db->prepare("SELECT * FROM reminder where deleted_at IS NULL AND task_id = ? order by id desc limit $limit");
		$stmt->execute([$id]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getRemindersByIdAndTodoId($todo_id, $id, $limit = 50)
	{
		$stmt = $this->db->prepare("SELECT * FROM reminder where deleted_at IS NULL AND task_id = ? AND id = ? order by id desc limit $limit");
		$stmt->execute([$todo_id, $id]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

}
