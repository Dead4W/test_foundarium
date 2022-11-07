<?php

namespace App\User\ResourceModels;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="User resource",
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(
     *     property="uuid",
     *     type="string",
     *     title="UUID",
     *     description="User UUID",
     *     example="d1b0c6be-e64d-4baf-acec-17e7c6162216"
     * )
     *
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     title="Name",
     *     description="User name",
     *     example="Ilya"
     * )
     *
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     title="Email",
     *     description="User email",
     *     example="test@example.com"
     * )
     */

    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
