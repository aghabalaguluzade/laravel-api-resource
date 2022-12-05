<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BannerCreateRequest;
use App\Http\Requests\Api\V1\BannerUpdateRequest;
use App\Http\Resources\Api\V1\BannerResource;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BannerResource::collection(Banner::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerCreateRequest $request)
    {
        $data = $request->validated();
        $image = $request->file("backgroundImage");
        $directory = "uploads/banners/";
        $image_name = Str::slug($request->title). '.' . $image->getClientOriginalExtension();
        $image->move($directory,$image_name);
        $image_name = $directory.$image_name;
        $data["backgroundImage"] = $image_name;
        $banner = Banner::create($data);
        return new BannerResource($banner);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        if($request->hasFile("backgroundImage")) {
            $data = $request->validated();
            $image = $request->file("backgroundImage");
            $directory = "uploads/banners/";
            $image_name = Str::slug($request->title). '.' . $image->getClientOriginalExtension();
            if(file_exists($banner->backgroundImage)) {
                unlink($banner->backgroundImage);
            };
            $image->move($directory,$image_name);
            $image_name = $directory.$image_name;
            $data["backgroundImage"] = $image_name;
            $banner->update($data);
            return new BannerResource($banner);
        };
        $banner->update($request->validated());
        return new BannerResource($banner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $deleted = $banner->delete();
        if($deleted) {
            if(file_exists($banner->backgroundImage)) {
                unlink($banner->backgroundImage);
            };
        };
        return response()->noContent();
    }
}