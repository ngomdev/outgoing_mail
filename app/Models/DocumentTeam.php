<?php

namespace App\Models;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentTeam extends Pivot
{
    use HasFactory;

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
