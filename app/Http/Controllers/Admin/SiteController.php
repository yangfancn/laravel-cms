<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\SiteForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Site::class, 'site');
    }

    public function edit(Site $site): Response
    {
        return SiteForm::render(
            route('admin.sites.update', 1),
            'Edit Site Setting',
            'PUT',
            $site
        );
    }

    public function update(SiteRequest $request, Site $site): RedirectResponse
    {
        $site->fill($request->all($site->getFillable()))->save();

        InertiaMessage::success(__('messages.updateSiteSuccess'));

        return redirect()->back();
    }
}
