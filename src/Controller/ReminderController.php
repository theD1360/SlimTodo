<?php

namespace Sample\Controller;

use \Sample\Controller\BaseController;
use \Sample\Model\ReminderModel;
use \Sample\Mapper\ReminderMapper;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class ReminderController extends BaseController {
	protected $mapperInstance;

	public function __construct($ci)
	{
		parent::__construct($ci);
		$this->mapperInstance = ReminderMapper::getInstance($ci->db);
	}

	public function index(Request $request, Response $response, $args) 
	{
   		$todo_id = $request->getAttribute('todo_id');

	    $list = $this->mapperInstance->getRemindersByTodoId($todo_id);
		return $response->withJson($list);
	}

	public function create(Request $request, Response $response, $args) 
	{
		$todo_id = $request->getAttribute('todo_id');

		$content = $request->getParsedBody();

		$newTodo = new ReminderModel([
			'task_id' => $todo_id,
			'remind_at'=> $content['remind_at']
		]);

		$this->mapperInstance->insert($newTodo);

		return $response->withJson($newTodo);
	}

	public function read(Request $request, Response $response, $args)
	{
		$todo_id = $request->getAttribute('todo_id');
		$id = $request->getAttribute('id');

		$todo = new ReminderModel($this->mapperInstance->getRemindersByIdAndTodoId($todo_id, $id));

	    return $response->withJson($todo);
	}

	public function update(Request $request, Response $response, $args) 
	{
		$todo_id = $request->getAttribute('todo_id');
		$id = $request->getAttribute('id');

		$content = $request->getParsedBody();

		$todo = new ReminderModel($this->mapperInstance->getRemindersByIdAndTodoId($todo_id, $id));
		$todo->massAssign($content);

		$this->mapperInstance->update($todo);

	    return $response->withJson($todo);
	}

	public function delete(Request $request, Response $response, $args) 
	{
		
		$id = $request->getAttribute('id');
		
		$todo = new ReminderModel([
			'id' => $id
		]);

	    return $response->withJson([
	    	'deleted' => $this->mapperInstance->delete($todo)
	    ]);
	}

}