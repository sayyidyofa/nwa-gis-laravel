<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Wilderness
 *
 * @property int $id
 * @property string $name
 * @property string $boundary_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness whereBoundaryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wilderness whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Wilderness extends Model
{
    //
    public function geometry() {
        return $this->belongsTo('App\Geometry', 'id', 'wildernesses_id');
    }

    protected $fillable = [
        'name', 'boundary_status', 'color',
    ];
}
