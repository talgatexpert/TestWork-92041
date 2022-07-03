<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DessertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resource = $this->resource->toArray();
        return [
            'success' => true,
            'data' => $resource['data'],
            'total' => $resource['total'],
            'last_page' => $resource['last_page'],
            'next_page_url' => $resource['next_page_url'],
            'prev_page_url' => $resource['prev_page_url'],
        ];
    }
}
