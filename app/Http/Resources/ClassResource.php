<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" =>  $this->id,
            "name" => $this->name,
            "level" => $this->level,
            "students" => $this->students,
            "courses" => $this->courses
        ];
    }
}
