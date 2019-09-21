<?php namespace obsession\Http\Controllers\Api\V1;

use obsession\Domain\Users\Profiles\Profile;
use obsession\Domain\Users\Users\Transformers\UserTransformer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use obsession\Domain\Users\Users\User;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testUserEndpoint()
    {
        $user = factory(User::class)->create();
        factory(Profile::class)->create(['user_id' => $user->id]);
        Passport::actingAs($user);

        $this
            ->getJson('/api/v1/user')
            ->assertStatus(200)
            ->assertExactJson((new UserTransformer)->transform($user));
    }

    public function testUserAsAnonymousEndpoint()
    {
        // Do not act as anyone to get Unauthenticated exception.
        $this
            ->getJson('/api/v1/user')
            ->assertStatus(401)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }
}
