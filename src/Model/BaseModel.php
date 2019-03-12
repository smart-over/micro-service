<?php

namespace SmartOver\MicroService\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder active()
 * @method static \Illuminate\Database\Eloquent\Builder deactive()
 * @method static \Illuminate\Database\Eloquent\Builder notDeleted()
 * @method static \Illuminate\Database\Eloquent\Builder findOrFail(int $id)
 */
class BaseModel extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Boot method
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        /**
         * We start to use isDeleted field
         * isActive field will be remove
         */
        return $query->where('isActive', 1);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeDeactive($query)
    {
        /**
         * We start to use isDeleted field
         * isActive field will be remove
         */
        return $query->where('isActive', 0);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('isDeleted', 0);
    }
}