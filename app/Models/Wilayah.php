<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Wilayah extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'wilayah';
    protected $primaryKey = 'kode_wilayah';
    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'mst_kode_wilayah', 'kode_wilayah');
    }
    public function parrentRecursive()
    {
        return $this->parent()->with('parrentRecursive');
    }
}
