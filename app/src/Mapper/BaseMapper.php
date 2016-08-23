<?php 

namespace Sample\Mapper;

class BaseMapper {

	protected $db;

	public function __construct(\PDO $db) {
		$this->db = $db;
	}


}
