<?php

namespace App\Http\Controllers\Api\Post;

use App\Models\User;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostSaveRequest;

class PostController extends Controller
{
    private function getPosts($filters = [])
    {

        $posts = Post::query()
            ->when(
                !$this->auth_user()->hasRole($this->admin_role),
                fn($q) => $q->whereHas(
                    'posts_owner',
                    fn($q) => $q->where('users.id', $this->auth_user()->id)
                )
            )
            ->when(
                $this->auth_user()->hasRole($this->admin_role),
                fn($q) => $q->with('posts_owner')
            )
            ->latest()
            ->get() ?? [];
        return \collect(['posts' => $posts]);
    }

    public function post(Post $post)
    {
        return \response()->json(['post' => $post]);
    }

    public function posts(Request $request)
    {
        $posts = $this->getPosts();
        return \response()->json(['posts' => $posts['posts']]);
    }

    public function createOrUpdate(PostSaveRequest $request)
    {
        $post = Post::updateOrCreate(['id' => $request->id], $request->validated());
        $admin_role = Role::find((int)\getSetting('admin_role'));
        if (!$this->auth_user()->hasRole($admin_role)) {
            $post->createReference("posts_1", $this->auth_user()->id, $post->id);
        }

        return \response()->json(['post' => $post]);
    }

    public function delete(Post $post)
    {
        $admin_role = Role::find((int)\getSetting('admin_role'));
        $post->deleteReference("posts_1", !$this->auth_user()->hasRole($admin_role) ? $this->auth_user()->id : null, $post->id);
        $post->delete();
        return \response()->json([], 204);
    }
}
