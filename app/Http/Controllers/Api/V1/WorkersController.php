<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\WorkersCreateRequest;
use App\Http\Requests\Api\V1\WorkersUpdateRequest;
use App\Http\Resources\Api\V1\WorkersResource;
use App\Models\Workers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WorkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WorkersResource::collection(Workers::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkersCreateRequest $request)
    {
        $data = $request->validated();
        $image = $request->file("img");
        $directory = "uploads/workers/";
        $image_name = Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
        $image->move($directory,$image_name);
        $image_name = $directory.$image_name;
        $data['img'] = $image_name;
        $workers = Workers::create($data);
        return new WorkersResource($workers);   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workers  $workers
     * @return \Illuminate\Http\Response
     */
    public function show(Workers $worker)
    {
        return new WorkersResource($worker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workers  $workers
     * @return \Illuminate\Http\Response
     */
    public function update(WorkersUpdateRequest $request, Workers $worker)
    {
        if($request->hasFile("img")) {
            $data = $request->validated();
            $image = $request->file("img");
            $directory = "uploads/workers/";
            $image_name = Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            if(file_exists($worker->img)) {
                unlink($worker->img);
            };
            $image->move($directory,$image_name);
            $image_name = $directory.$image_name;
            $data["img"] = $image_name;
            $worker->update($data);
            return new WorkersResource($worker);       
        };
        $worker->update($request->validated());
        return new WorkersResource($worker);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workers  $workers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workers $worker)
    {
        $deleted = $worker->delete();
        if($deleted) {
            if(file_exists($worker->img)) {
                unlink($worker->img);
            };
        };
        return response()->noContent();
    }
}
