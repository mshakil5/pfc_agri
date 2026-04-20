<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMemberTranslation extends Model
{
    protected $fillable = ['team_member_id', 'locale', 'designation', 'bio'];
}