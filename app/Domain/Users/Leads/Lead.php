<?php namespace obsession\Domain\Users\Leads;

use obsession\Infrastructure\
{
    Interfaces\Domain\Users\Users\HandshakableInterface,
    Contracts\Model\ModelAbstract,
    Contracts\Model\Notifiable,
    Contracts\Model\IdentifiableTrait,
    Contracts\Model\TimeStampsTz,
    Contracts\Model\SoftDeletesTz
};
use obsession\Domain\Users\
{
    Leads\Traits\HandshakeNotificationTrait,
    Users\User,
    Users\Traits\NamableTrait
};

class Lead extends ModelAbstract implements HandshakableInterface
{

    use Notifiable;
    use IdentifiableTrait;
    use HandshakeNotificationTrait;
    use NamableTrait;
    use TimeStampsTz;
    use SoftDeletesTz;

    /**
     * @var string
     */
    protected $table = 'users_leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'civility',
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the user record associated with the lead.
     */
    public function user()
    {
        return $this
            ->belongsTo(User::class)
            ->withTrashed();
    }
}
