<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $this->image,
            'title' => $this->title,
            'short_text' => $this->short_text,
            'full_text' => $this->full_text,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'phone_number' => $this->phone_number,
            'website' => $this->website,
        ];
    }
}
