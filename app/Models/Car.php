<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Cars
 *
 * @property int $id
 * @property string $title
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\CarFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Car newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car query()
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereUserId($value)
 * @mixin \Eloquent
 * @property string $company
 * @property string $model_family
 * @property string $model_number
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereModelFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereModelNumber($value)
 */
class Car extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
