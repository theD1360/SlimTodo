<?php

namespace Sample\Model;


class BaseModel {

	protected $gaurded = [];

	public $id = null;

	public function __construct($props) 
	{
		$this->massAssign($props);
	}

	public function massAssign($props)
	{
		foreach ($props as $k => $v) {
				if (!in_array($k,$this->gaurded)) {
					$this->{$k} = $this->mutate($k, $v);
				}
		}	
	}

	private function mutate($k, $v)
	{
		$c = get_called_class();
		if (array_key_exists($k, $this->mutators)) {
			$m = $this->mutators[$k];
			return call_user_func([$c, $m], $v);
		} else {
			return $v;
		}
	}

}