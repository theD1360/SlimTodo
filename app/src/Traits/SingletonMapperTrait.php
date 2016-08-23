<?php 

namespace Sample\Traits;

trait SingletonMapperTrait {
	static $_instance = null;

	public function getInstance(\PDO $db)
	{

		if (!self::$_instance) {
			$c = __CLASS__;
			self::$_instance = new $c($db);
		} 

		return self::$_instance;
	}
}