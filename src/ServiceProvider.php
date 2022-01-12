<?php

namespace Mncs\StatamicValidateUploads;

use Mncs\StatamicValidateUploads\Http\Controllers\AssetsController as AssetsControllerExtend;
use Statamic\Http\Controllers\CP\Assets\AssetsController;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    /**
     * @var string[]
     */
    public $bindings = [
        AssetsController::class => AssetsControllerExtend::class,
    ];

    public function bootAddon()
    {
        //
    }
}
