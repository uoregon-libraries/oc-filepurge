<?php
// The Automatic File Purge is in the public domain under a CC0 license.

namespace OCA\AutomaticFilePurge\AppInfo;


use \OCP\AppFramework\App;
use \OCP\IContainer;

class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('automaticfilepurge', $urlParams);

		$container = $this->getContainer();
	}


}
