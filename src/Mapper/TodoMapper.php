<?php 

namespace Sample\Mapper;

use Sample\Mapper\BaseMapper;
use Sample\Model\TodoModel;
use Sample\Mapper\ReminderMapper;
use Sample\Traits\SingletonMapperTrait;
use Carbon\Carbon;

class TodoMapper extends BaseMapper{

	use SingletonMapperTrait;

	public function getList($limit = 50)
	{
		$stmt = $this->db->prepare("SELECT t.id, t.note, t.created_at, t.deleted_at, t.updated_at, count(r.id) as reminders 
			FROM tasks as t 
			left join (select id, task_id from reminder where deleted_at is null) as r on r.task_id = t.id 
			where t.deleted_at IS NULL  
			group by t.id 
			order by id desc 
			limit $limit");
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function insert(TodoModel $todo)
	{
		$stmt = $this->db->prepare('insert into tasks (note) values (?)');
		$stmt->execute([$todo->note]);
		$id = $this->db->lastInsertId();
		$todo->massAssign($this->getTodoById($id));

		return $todo;
	}

	public function update(TodoModel $todo)
	{
		$stmt = $this->db->prepare('update tasks set note = ? where id =
			?');
		$stmt->execute([$todo->note, $todo->id]);

		return $todo->massAssign($this->getTodoById($todo->id));
	}

	public function delete(TodoModel $todo)
	{
		$stmt = $this->db->prepare('update tasks set deleted_at = now() where id =
			?');
		return $stmt->execute([$todo->id]);
	}


	public function getTodoById($id)
	{
		$stmt = $this->db->prepare('SELECT * FROM tasks where id = ? limit 1');
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function loadReminders(TodoModel $todo)
	{
		$reminders = ReminderMapper::getInstance($this->db)->getRemindersByTodoId($todo->id);
		$todo->reminders = $reminders;
	}

	public function getTodosByReminderOnDay(Carbon $datetime, $limit = 50) 
	{
		$stmt = $this->db->prepare("SELECT t.id, t.note, t.created_at, t.deleted_at, t.updated_at, group_concat(TIME(r.remind_at)) as remind_at_hours
			FROM reminder as r 
			left join (select * from tasks where deleted_at is null) as t on r.task_id = t.id 
			where r.deleted_at IS NULL AND DATE(r.remind_at) = DATE( ? ) 
			group by t.id 
			order by id desc 
			limit $limit");
		$stmt->execute([$datetime->toDateTimeString()]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


}
