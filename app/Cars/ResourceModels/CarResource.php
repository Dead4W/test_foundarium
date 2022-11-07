<?php

namespace App\Cars\ResourceModels;

use App\Cars\Enums\CarStateEnum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Car resource",
 * )
 *
 * @OA\Property(
 *     property="uuid",
 *     type="string",
 *     title="UUID",
 *     description="Car UUID",
 *     example="d1b0c6be-e64d-4baf-acec-17e7c6162216"
 * )
 *
 * @OA\Property(
 *     property="company",
 *     type="string",
 *     title="Company name",
 *     description="Car company name",
 *     example="BMW"
 * )
 *
 * @OA\Property(
 *     property="model_family",
 *     type="string",
 *     title="Model family",
 *     description="Car model family",
 *     example="X"
 * )
 *
 * @OA\Property(
 *     property="model_number",
 *     type="string",
 *     title="Model number",
 *     description="Car model number",
 *     example="100"
 * )
 *
 * @OA\Property(
 *     property="state",
 *     type="string",
 *     title="State",
 *     description="Car state",
 *     enum={"free", "busy"},
 *     example="free"
 * )
 */
class CarResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'company' => $this->company,
            'model_family' => $this->model_family,
            'model_number' => $this->model_number,
            'state' => $this->user_id !== null ? CarStateEnum::BUSY : CarStateEnum::FREE,
        ];
    }
}
