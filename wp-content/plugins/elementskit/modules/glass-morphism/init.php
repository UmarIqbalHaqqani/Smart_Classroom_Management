<?php
namespace ElementsKit\Modules\Glass_Morphism;

defined( 'ABSPATH' ) || exit;

class Init {
	private $dir;
	private $url;

	public function __construct() {

		// get current directory path
		$this->dir = dirname(__FILE__) . '/';

		// get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/glass-morphism/';

		// include all necessary files
		$this->include_files();

		// calling the wrapper controls
		new \Elementor\ElementsKit_Glass_Morphism();
		
	}

	public function include_files() {
		include $this->dir . 'glass-morphism.php';
	}
}