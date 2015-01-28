<?php

	class TestEnv {

		protected static $instance = null;
		 
		private $exclude_test_class_names = array('TestEnv');
		private $std_class_num = 134;
		
		//
		// TO-DO: Implement Test Log Stack
		//
		private $test_stack;
		
		//
		// The constructor is enforced to allowed to be called by the method inst() once only. 
		//
		public function __construct() {

			if (null === TestEnv::$instance) {
				
				$trace = debug_backtrace();
				
				if (
						(count($trace) > 1)
						&&
						('TestEnv' === $trace[1]['class'])
						&&
						('inst' === $trace[1]['function'])
					) {

					//pass case
					TestEnv::$instance = $this;
					return;
					
				}

			}
			
			throw new Exception('Singleton class cannot be instantiated with new method');
			
		}

		//
		// Method inst() returns the Singleton instance 
		//
		public static function inst() {
			
			if (null === TestEnv::$instance) {
				
				TestEnv::$instance = new TestEnv();

			}
			
			return TestEnv::$instance;
			
		}
		
		//
		// TO-DO: Implement flag for START and END.
		//
		public function log($args=null) {

			if (null === $args) {
			
				$trace = debug_backtrace();
	
				if (count($trace) > 1) {
				
					echo $trace[1]['function'] . '<br />';
					
				} else {

					echo 'backtrace unavailable<br />';
					
				}
			
			}
			
		}
		
		private function get_class_names() {
			
			$result = array();
			
			$classes = get_declared_classes();
			
			for ($i=$this->std_class_num; $i<count($classes); $i++) {
				array_push($result, $classes[$i]);
			} 
			
			return $result;
			
		}
		
		private function get_function_names() {
			
			$functions = get_defined_functions();

			return $functions['user'];
			
		}
		
		public function test_all_functions() {
			
			foreach ($this->get_function_names() as $function_name) {
			
				call_user_func($function_name);
				
			}
			
		}
		
		public function test_all_classes_methods() {
			
			foreach (array_diff(
						$this->get_class_names(), $this->exclude_test_class_names
					 )
						 as $class_name) {
			
				$this_class = new $class_name();
			
				foreach (get_class_methods($this_class) as $method_name) {
						
					$this_class->{$method_name}();
						
				}
			
			}
			
		}
		
	}

	class Math {
		
		function add($x, $y) {
			TestEnv::inst()->log();
			return $x + $y;
		}
		
	}
	
	function funcA() {
		TestEnv::inst()->log();
	}

	//
	//Initialize Testing
	//
	$te = TestEnv::inst();
	
 	$te->test_all_functions();
 	$te->test_all_classes_methods();
	
?>