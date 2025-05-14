<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gpu extends Model
{
    protected $fillable = ['name', 'performance_score'];
}