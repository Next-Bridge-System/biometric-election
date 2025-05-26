<?php

namespace App\Traits;

trait ResetsPagination
{
    public function updated()
    {
        $this->resetPage();
    }
}
