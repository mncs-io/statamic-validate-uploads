<?php

namespace Mncs\StatamicValidateUploads\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Statamic\Facades\AssetContainer;
use Statamic\Facades\YAML;
use Statamic\Http\Controllers\CP\Assets\AssetsController as BaseAssetsController;

class AssetsController extends BaseAssetsController
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Statamic\Http\Resources\CP\Assets\Asset|void
     */
    public function store(Request $request)
    {
        $request->validate([
            'container' => 'required',
        ]);
        $container = AssetContainer::find($request->container);

        $path = config('statamic.stache.stores.asset-containers.directory', base_path('content/assets'));
        $assetConfig = YAML::parse(file_get_contents(rtrim($path, '/').'/'.$container->handle().'.yaml'));

        if (!Arr::has($assetConfig, 'validation')) {
            parent::store($request);
        }

        $validator = Validator::make($request->only('file'), [
            'file' => Arr::get($assetConfig, 'validation'),
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            @unlink($request->file('file')->path());
            abort(422, $validator->errors()->first());
        }

        parent::store($request);
    }
}
