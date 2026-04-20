<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'image', 'status', 'serial'];

    // Custom translation relationship
    public function translations()
    {
        return $this->hasMany(TeamMemberTranslation::class);
    }

    // Custom helper method (matches your other models like Product & Category)
    public function translateOrNew($locale)
    {
        $translation = $this->translations->where('locale', $locale)->first();
        if (!$translation) {
            $translation = new TeamMemberTranslation();
            $translation->team_member_id = $this->id;
            $translation->locale = $locale;
        }
        return $translation;
    }
}