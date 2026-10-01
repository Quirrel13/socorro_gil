<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public $timestamps = false;

    public function role() {
        return $this->belongsTo('\App\Models\Role');
    }

    public function resource() {
        return $this->belongsTo('\App\Models\Resource');
    }
}
