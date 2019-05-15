<?php

namespace SmartOver\MicroService\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @method static BaseModel  active()
 * @method static BaseModel deactive()
 * @method static BaseModel notDeleted()
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder findOrFail(int $id)
 * @method static \Illuminate\Database\Eloquent\Builder userId(string $userId)
 * @mixin Model
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
        return $query->where($this->table . '.isDeleted', 0);
    }

    /**
     * @param $query
     * @param $userId
     *
     * @return mixed
     */
    public function scopeUserId($query, $userId)
    {

        if ($userId) {

            $query = $query->where('userId', $userId);

        }

        return $query;

    }
}
