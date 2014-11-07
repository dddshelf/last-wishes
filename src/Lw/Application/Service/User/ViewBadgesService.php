<?php

namespace Lw\Application\Service\User;

class ViewBadgesService
{
    public function execute($request = null)
    {
        $content = file_get_contents('http://localhost:8081/user/'.$request->userId());
        return json_decode($content);
    }
}
