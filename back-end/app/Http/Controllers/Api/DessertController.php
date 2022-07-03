<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DessertRequest;
use App\Http\Resources\Api\DessertResource;
use App\Models\Dessert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DessertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return DessertResource
     */
    public function index(Request $request): DessertResource
    {
        $limit = $request->get('limit') === "all" ? 1000 : $request->get('limit') ?? 10;
        $desserts = Dessert::where('user_id', auth()->id())
            ->orderBy($request->get('orderby') ?? 'id', $request->get('sort') ?? 'DESC')
            ->paginate($limit);

        return new DessertResource($desserts);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DessertRequest $request
     * @return JsonResponse
     */
    public function store(DessertRequest $request): JsonResponse
    {


        $dessert = Dessert::create($request->validated() + ['user_id' => auth()->id()]);

        return response()->successWithMessage('Dessert successfully created', $dessert);
    }

    /**
     * Display the specified resource.
     *
     * @param Dessert $dessert
     * @return JsonResponse
     */
    public function show(Dessert $dessert): JsonResponse
    {
        return response()->success($dessert);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param DessertRequest $request
     * @param Dessert $dessert
     * @return JsonResponse
     */
    public function update(DessertRequest $request, Dessert $dessert): JsonResponse
    {
        $dessert->update($request->validated());
        return response()->successWithMessage('Dessert successfully updated', $dessert);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Dessert $dessert
     * @return JsonResponse
     */
    public function destroy(Dessert $dessert): JsonResponse
    {
        $dessert->delete();
        return response()->successWithMessage('Dessert successfully deleted');
    }
}
