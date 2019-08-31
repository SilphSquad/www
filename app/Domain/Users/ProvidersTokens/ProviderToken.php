<?php namespace obsession\Domain\Users\ProvidersTokens;

use obsession\Infrastructure\
{
    Interfaces\Domain\Users\ProvidersTokens\ProvidersInterface,
    Contracts\Model\ModelAbstract,
    Contracts\Model\Notifiable,
    Contracts\Model\IdentifiableTrait,
    Contracts\Model\TimeStampsTz
};
use obsession\Domain\Users\Users\
{
    User
};

class ProviderToken extends ModelAbstract implements ProvidersInterface
{

    use Notifiable;
    use IdentifiableTrait;
    use TimeStampsTz;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_providers_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the user record associated with the provider_token.
     */
    public function user()
    {
        return $this
            ->belongsTo(User::class)
            ->withTrashed();
    }
}
