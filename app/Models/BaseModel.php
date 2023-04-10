<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $transformer = null;

    public function transform()
    {
        if (empty($this->transformer) || !class_exists($this->transformer)) {
            return $this;
        }

        return (new $this->transformer())
            ->transform($this, func_get_args());
    }
}
