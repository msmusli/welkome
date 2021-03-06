<?php

namespace App\Welkome;

use App\Traits\Queryable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use Queryable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'team_member_name', 'team_member_email'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['hash'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'pivot', 'user_id', 'hotel_id'];

    /**
     * Hashing Product ID.
     *
     * @return string
     */
    public function getHashAttribute()
    {
        return $this->attributes['hash'] = (string) id_encode($this->attributes['id']);
    }

    /**
     * The shifts that belong to the note.
     */
    public function shifts()
    {
        return $this->belongsToMany(\App\Welkome\Shift::class);
    }

    /**
     * The tags that belong to the note.
     */
    public function tags()
    {
        return $this->belongsToMany(\App\Welkome\Tag::class);
    }

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Get the hotel that owns the note.
     */
    public function hotel()
    {
        return $this->belongsTo(\App\Welkome\Hotel::class);
    }

    /**
     * Scope a query to get all notes by a hotel and a tag.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Welkome\Hotel $hotel
     * @param  \App\Welkome\Tag $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTag($query, Hotel $hotel, Tag $tag)
    {
        return $query->whereUserId(id_parent())
            ->whereHotelId($hotel->id)
            ->whereHas('tags', function ($query) use ($tag)
            {
                $query->where('id', $tag->id);
            });
    }
}
