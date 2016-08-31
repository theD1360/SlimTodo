<?php 

namespace Sample\Controller;

use \Sample\Controller\BaseController;
use \Sample\Model\TodoModel;
use \Sample\Mapper\TodoMapper;
use Carbon\Carbon;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class TodoController extends BaseController {
	protected $mapperInstance;

	public function __construct($ci)
	{
		parent::__construct($ci);
		$this->mapperInstance = TodoMapper::getInstance($ci->db);
	}

	public function index(Request $request, Response $response, $args) 
	{
	    
	    $list = $this->mapperInstance->getList();
		return $response->withJson($list);
	}

	public function create(Request $request, Response $response, $args) 
	{
		$content = $request->getParsedBody();

		$newTodo = new TodoModel([
			'note'=> $content['note']
		]);

		$this->mapperInstance->insert($newTodo);

		return $response->withJson($newTodo);
	}

	public function read(Request $request, Response $response, $args)
	{
		$id = $request->getAttribute('id');

		$todo = new TodoModel($this->mapperInstance->getTodoById($id));
		$this->mapperInstance->loadReminders($todo);

	    return $response->withJson($todo);
	}

	public function update(Request $request, Response $response, $args) 
	{
		$id = $request->getAttribute('id');

		$content = $request->getParsedBody();

		$todo = new TodoModel([
			'id' => $id,
			'note' => $content['note']
		]);
		
		$this->mapperInstance->update($todo);

	    return $response->withJson($todo);
	}

	public function delete(Request $request, Response $response, $args) 
	{
		
		$id = $request->getAttribute('id');
		
		$todo = new TodoModel([
			'id' => $id
		]);

	    return $response->withJson([
	    	'deleted' => $this->mapperInstance->delete($todo)
	    ]);
	}



	public function today(Request $request, Response $response, $args)
	{
		$now = Carbon::now();
		$list = $this->mapperInstance->getTodosByReminderOnDay($now);
		return $response->withJson($list);
	}

}