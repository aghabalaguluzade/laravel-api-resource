<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SiteCreateRequest;
use App\Http\Requests\Api\V1\SiteUpdateRequest;
use App\Http\Resources\Api\V1\SiteResource;
use App\Models\Site;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SiteResource::collection(Site::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteCreateRequest $request)
    {
        $data = $request->validated();
        $image = $request->file("img");
        $directory = "uploads/sites/";
        $image_name = Str::slug($request->title). '.' . $image->getClientOriginalExtension();
        $image->move($directory,$image_name);
        $image_name = $directory.$image_name;
        $data["img"] = $image_name;
        $site = Site::create($data);
        return new SiteResource($site);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        return new SiteResource($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(SiteUpdateRequest $request, Site $site)
    {
        if($request->hasFile("img")) {
            $data = $request->validated();
            $image = $request->file("img");
            $directory = "uploads/sites/";
            $image_name = Str::slug($request->title). '.' . $image->getClientOriginalExtension();
            if(file_exists($site->img)) {
                unlink($site->img);
            };
            $image->move($directory,$image_name);
            $image_name = $directory.$image_name;
            $data["img"] = $image_name;
            $site->update($data);
            return new SiteResource($site);
        };
        $site->update($request->validated());
        return new SiteResource($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $deleted = $site->delete();
        if($deleted) {
            if(file_exists($site->img)) {
                unlink($site->img);
            };
        };
        return response()->noContent();
    }
}
