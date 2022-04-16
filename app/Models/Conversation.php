<?php

namespace App\Models;

use Musonza\Chat\Models\Conversation as BaseModel;

class Conversation extends BaseModel
{
    /**
     * Messages in conversation.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    /**
     * Return the recent message in a Conversation.
     *
     * @return HasOne
     */
    public function last_message()
    {
        return $this->hasOne(Message::class)->orderBy($this->tablePrefix . 'messages.id', 'desc');
    }

    public function getDataAttribute($data)
    {
        $participant = $this->participants()
            ->where('messageable_id', '!=', auth()->id())
            ->first()
            ->messageable()
            ->select('id', 'name', 'avatar', 'contact_by', 'badge_verified_at')
            ->without('participation')
            ->get()
            ->first();

        return ['user' => $participant->toArray()];
    }
}

