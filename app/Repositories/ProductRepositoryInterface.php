<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function create(array $data): Product;

    public function findByName($name);

    public function find($id);

    public function all();

    public function update($id, array $data);

    public function delete($id);

    public function countSlugs($slug);
}
