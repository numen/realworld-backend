<?php

namespace User\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ProfileModel extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = ['name', 'bio', 'image'];
}
