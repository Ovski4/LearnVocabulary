<?php

namespace Ovski\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OvskiUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
