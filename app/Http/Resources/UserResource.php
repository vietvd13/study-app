<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-03
 */

namespace App\Http\Resources;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
