<?php

namespace App\Query;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class RandomSampleQuery
 *
 * Random sample of documents from a collection
 */
class RandomSampleQuery
{
    public function __construct(private readonly Model $model)
    {
        //
    }

    public function execute(int $size = 5)
    {
        return $this->model->raw(function ($collection) use ($size) {
            return $collection->aggregate([
                ['$sample' => ['size' => $size]]
            ]);
        });
    }
}
