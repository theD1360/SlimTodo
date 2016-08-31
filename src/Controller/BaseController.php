<?php 

namespace Sample\Controller;

use Slim\Container as ContainerInterface;

class BaseController {
	protected $ci;
   
	public function __construct(ContainerInterface $ci) {
	   $this->ci = $ci;
	}
}