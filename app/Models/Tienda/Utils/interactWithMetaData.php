<?php

namespace App\Models\Tienda\Utils;
use App\Models\Tienda\MetaData;

trait interactWithMetaData
{

  public function info()
	{
    return $this->hasMany(MetaData::class, 'post_id');
  }
  
  public function getInfoKeyValue($key)
	{
		return optional($this->info->where('meta_key' , $key)->first())->meta_value;
	}
}