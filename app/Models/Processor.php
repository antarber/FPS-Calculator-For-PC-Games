<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    protected $fillable = ['name', 'performance_score'];
}