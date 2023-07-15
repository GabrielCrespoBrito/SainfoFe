<?php

namespace App\Repositories;

use App\Vendedor;

class VendedorRepository extends RepositoryBase
{
	public $prefix_key = "VENDEDOR";

	public function __construct(Vendedor $model, $empcodi = null)
	{
		parent::__construct($model, $empcodi);
	}

	public function create($data)
	{
		$this->model->create($data);
	}

	public function update(array $data, $id)
	{
		$model = $this->model->findOrfail($id);
		$model->fill($data);
		if ($model->isDirty()) {
			$model->save();
		}
	}

	public function delete($id)
	{
		$model = $this->model->findOrfail($id);
		$model->delete();

    $this->clearCache('all');
	}
}
