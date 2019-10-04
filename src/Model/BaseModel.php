<?php

namespace SmartOver\MicroService\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class BaseModel
 *
 * @method static BaseModel|Builder notDeleted()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel findOrFail(int $id)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel find(int $id)
 * @mixin Model
 */
class BaseModel extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public static $snakeAttributes = false;

    /**
     * Boot method
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (in_array('creationTime', $model->fillable)) {
                $model->setAttribute('creationTime', time());
            }
        });

        static::updating(function (Model $model) {
            if (in_array('updatingTime', $model->fillable)) {
                $model->setAttribute('updatingTime', time());
            }
        });
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotDeleted($query)
    {
        return $query->where($this->table . '.isDeleted', 0);
    }

}
