<?php
// The Automatic File Purge is in the public domain under a CC0 license.

namespace OCA\AutomaticFilePurge\Controller;


use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;

class PageController extends Controller {

    private $userId;

    public function __construct($appName, IRequest $request, $userId){
        parent::__construct($appName, $request);
        $this->userId = $userId;
    }


    /**
     * CAUTION: the @Stuff turn off security checks, for this page no admin is
     *          required and no CSRF check. If you don't know what CSRF is, read
     *          it up in the docs or you might create a security hole. This is
     *          basically the only required method to add this exemption, don't
     *          add it to any other method if you don't exactly know what it does
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
        $params = array('user' => $this->userId);
        return new TemplateResponse('automaticfilepurge', 'main', $params);  // templates/main.php
    }


    /**
     * Simply method that posts back the payload of the request
     * @NoAdminRequired
     */
    public function doEcho($echo) {
        return array('echo' => $echo);
    }


}
