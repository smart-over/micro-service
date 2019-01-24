<?php

namespace App\Models;

use SmartOver\MicroService\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder active()
 * @method static \Illuminate\Database\Eloquent\Builder deactive()
 * @method static \Illuminate\Database\Eloquent\Builder findOrFail(int $id)
 */
class BaseMultiLangModel extends BaseModel
{
    /**
     * @var
     */
    protected $langTable;

    /**
     * @var
     */
    protected $foreignField;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @var array
     */
    protected $translatableFields = [];

    /**
     * @var
     */
    protected $translateModel;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeMultiLang(Builder $query)
    {

        $currentLang = app('translator')->getLocale();
        $defaultLang = config('app.defaultLocale');

        if ($currentLang != $defaultLang) {

            $query = $query->leftJoin($this->langTable, function ($join) use ($currentLang) {

                $join->on($this->table.'.id', '=', $this->langTable.'.'.$this->foreignField)->where($this->langTable.'.langCode', '=', $currentLang);
            });

            $langTableSelectFields = [];

            foreach ($this->translatableFields as $key) {

                array_push($langTableSelectFields, $this->langTable.'.translate'.ucfirst($key));
            }

            array_push($langTableSelectFields, $this->table.'.*');

            return $query->select($langTableSelectFields);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function attributesToArray()
    {

        $attributes = $this->addDateAttributesToArray($attributes = $this->getArrayableAttributes());

        $attributes = $this->addMutatedAttributesToArray($attributes, $mutatedAttributes = $this->getMutatedAttributes());

        $attributes = $this->addCastAttributesToArray($attributes, $mutatedAttributes);

        foreach ($this->getArrayableAppends() as $key) {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        foreach ($this->translatableFields as $key) {

            if ($this->getAttribute('translate'.ucfirst($key))) {

                $attributes[$key] = $this->getAttribute('translate'.ucfirst($key));
            }
        }

        return $attributes;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed|void
     * @throws \Exception
     */
    public function setAttribute($key, $value)
    {
        if (is_array($value)) {

            $allValues = $value;
            if (! isset($allValues [config('app.defaultLocale')])) {
                throw new \Exception($key.' has not contain default lang value');
            }

            $value = $value[config('app.defaultLocale')];
            unset($allValues[config('app.defaultLocale')]);

            foreach ($allValues as $language => $foo) {
                $this->translations[$language][$key] = $foo;
            }
        }
        $this->attributes[$key] = $value;
    }

    /**
     * Multi language model boot
     */
    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            self::translate($model);
        });

        static::saved(function ($model) {
            self::translate($model);
        });
    }

    /**
     * @param $model
     * @return void
     */
    public static function translate($model)
    {

        if (count($model->translations) > 0) {

            foreach ($model->translations as $language => $value) {

                $translateModel = ($model->translateModel)::firstOrNew([
                    $model->foreignField => $model->id,
                    'langCode' => $language,
                ]);

                foreach ($value as $field => $fieldValue) {

                    $translateModel->{'translate'.ucfirst($field)} = $fieldValue;
                }
                $translateModel->save();
            }
        }
    }
}
