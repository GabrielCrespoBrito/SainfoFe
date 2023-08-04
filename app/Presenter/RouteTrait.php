<?php

namespace App\Presenter;

trait RouteTrait
{
  public function getRouteName()
  {
    return $this->routeName;
  }

  public function routeIndex()
  {
    return route($this->getRouteName() . '.index');
  }

  public function routeEdit()
  {
    return route($this->getRouteName() . '.edit', ['id' => $this->getId()  ]);
  }

  public function routeShow()
  {
    return route($this->getRouteName() . '.show', ['id' => $this->getId()  ]);
  }

  public function routeDelete()
  {
    return route($this->getRouteName() . '.destroy', ['id' => $this->getId()  ]);
  }

  public function routeUpdate()
  {
    return route($this->getRouteName() . '.update', ['id' => $this->getId()  ]);
  }

}
