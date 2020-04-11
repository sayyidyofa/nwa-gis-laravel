<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Geometry
 *
 * @property int $id
 * @property string $geotype
 * @property string $coordinates
 * @property int $wildernesses_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereGeotype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Geometry whereWildernessesId($value)
 * @mixin \Eloquent
 */
class Geometry extends Model
{
    //
    public function wilderness() {
        return $this->hasOne('App\Wilderness');
    }

    protected $fillable = [
        'geotype', 'coordinates', 'wildernesses_id'
    ];
}
