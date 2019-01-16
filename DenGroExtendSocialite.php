<?php

namespace SocialiteProviders\DenGro;

use SocialiteProviders\Manager\SocialiteWasCalled;

class DenGroExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('dengro', __NAMESPACE__.'\Provider');
    }
}
