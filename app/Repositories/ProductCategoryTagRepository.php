<?php

namespace App\Repositories;

use App\Models\ProductCategoryTag;

class ProductCategoryTagRepository
{

    protected $model;

    public function __construct(ProductCategoryTag $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function all()
    {
        return $this->model->all();
    }
}
