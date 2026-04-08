<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\Sluggable;
use App\Models\Traits\SyncMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token', 'pivot'])]
class User extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasRoles;
    use InteractsWithMedia;
    use LogsActivity;
    use Notifiable;
    use Sluggable;
    use SyncMedia;

    public string $slugPrefix = 'authors/';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(name: 'avatar')
            ->singleFile();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public static function booted(): void
    {
        static::created(fn (User $user) => $user->assignRole(['commenter', 'voter']));
    }
}
