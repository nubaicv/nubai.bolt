<?php

namespace Bundle\Nubai\Storage\Session;

use Bundle\Nubai\Storage\Repository\SessionsRepository;

/**
 * Description of SessionHandler
 *
 * @author ricardo
 */
class SessionHandler {

    function open($savePath, $sessionName) {
        return true;
    }

    function close() {
        return 1;
    }

    function read($sessionId) {
        
    }

    function write($sessionId, $data) {
        
    }

    function destroy($sessionId) {
        
    }

    function gc($lifetime) {
        
    }

}
