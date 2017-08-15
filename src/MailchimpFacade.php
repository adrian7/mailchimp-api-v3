<?php

namespace MailChimp;

use Illuminate\Support\Facades\Facade;

class MailChimpFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'MailChimp\API';
    }

}
