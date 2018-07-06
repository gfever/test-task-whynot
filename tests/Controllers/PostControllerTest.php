<?php namespace Tests\Controllers;

use App\Http\Controllers\PostController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @author d.ivaschenko
 */
class PostControllerTest extends TestCase
{

    public function testIndexAdmin()
    {
        $user = resolve(User::class);
        $user->role = User::ROLE_ADMIN;
        $this->be($user);

        $builderMock = $this->createMock(Builder::class);
        $builderMock->expects($this->once())->method('paginate')->willReturn(\Mockery::mock([
            'getCollection' => collect([]),
            'links' => ''
        ]));

        $this->instance(Post::class, $builderMock);

        /** @see PostController::index() */
        $response = $this->get(action('PostController@index'))->baseResponse;
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexUser()
    {
        $user = resolve(User::class);
        $user->role = User::ROLE_USER;
        $this->be($user);

        $builderMock = $this->createMock(Builder::class);
        $builderMock->expects($this->once())->method('where')->willReturnSelf();
        $builderMock->expects($this->once())->method('paginate')->willReturn(\Mockery::mock([
            'getCollection' => collect([]),
            'links' => ''
        ]));

        $this->instance(Post::class, $builderMock);

        $response = $this->get(action('PostController@index'))->baseResponse;
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testIndexGuest()
    {
        $response = $this->get(action('PostController@index'))->baseResponse;
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostCreate()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = resolve(User::class);
        $user->id = 1;
        $user->role = User::ROLE_USER;
        $this->be($user);

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->once())->method('toArray')->willReturn(['title' => 'title', 'body' => 'body']);

        $this->instance(Request::class, $requestMock);

        $builderMock = \Mockery::mock([
            'create' => 1,
        ]);

        $this->instance(Post::class, $builderMock);

        /** @see PostController::store() */
        $response = $this->post(action('PostController@store'))->baseResponse;
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostEdit()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = resolve(User::class);
        $user->id = 1;
        $user->role = User::ROLE_USER;
        $this->be($user);

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->once())->method('except')->willReturn(['title' => 'title', 'body' => 'body']);

        $this->instance(Request::class, $requestMock);

        $postMock = $this->getMockBuilder(Post::class)->setMethods(['resolveRouteBinding', 'update'])->getMock();
        $postMock->id = 1;
        $postMock->user_id = 1;
        $postMock->expects($this->once())->method('resolveRouteBinding')->willReturn(true);
        $postMock->expects($this->once())->method('update');

        $this->instance(Post::class, $postMock);

        /** @see PostController::update() */
        $response = $this->put(action('PostController@update', ['post' => 1]))->baseResponse;
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostDelete()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = resolve(User::class);
        $user->id = 1;
        $user->role = User::ROLE_USER;
        $this->be($user);

        $postMock = $this->getMockBuilder(Post::class)->setMethods(['resolveRouteBinding', 'delete'])->getMock();
        $postMock->id = 1;
        $postMock->user_id = 1;
        $postMock->expects($this->once())->method('resolveRouteBinding')->willReturn(true);
        $postMock->expects($this->once())->method('delete');

        $this->instance(Post::class, $postMock);

        /** @see PostController::destroy() */
        $response = $this->delete(action('PostController@destroy', ['post' => 1]))->baseResponse;
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostPublishToggle()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = resolve(User::class);
        $user->id = 1;
        $user->role = User::ROLE_ADMIN;
        $this->be($user);

        $postMock = $this->getMockBuilder(Post::class)->setMethods(['resolveRouteBinding', 'save'])->getMock();
        $postMock->id = 1;
        $postMock->user_id = 1;
        $postMock->published = false;
        $postMock->expects($this->once())->method('resolveRouteBinding')->willReturn(true);
        $postMock->expects($this->once())->method('save');

        $this->instance(Post::class, $postMock);

        /** @see PostController::publishToggle() */
        $response = $this->post(action('PostController@publishToggle', ['post' => 1]))->baseResponse;
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($postMock->published);
    }
}