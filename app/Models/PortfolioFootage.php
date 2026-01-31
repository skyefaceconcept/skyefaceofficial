<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioFootage extends Model
{
    use HasFactory;

    protected $table = 'portfolio_footages';

    protected $fillable = [
        'portfolio_id',
        'type',
        'media_path',
        'title',
        'description',
        'display_order',
    ];

    /**
     * Get the portfolio this footage belongs to
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Check if footage is a video
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Check if footage is a photo
     */
    public function isPhoto()
    {
        return $this->type === 'photo';
    }
}
