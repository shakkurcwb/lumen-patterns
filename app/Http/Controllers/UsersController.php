<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;

class UsersController extends Controller
{
    protected $repo_user;

    private $filters =
    [
        //
    ];

    private $sorters =
    [
        'id' => 'DESC',
    ];

    public function __construct(UserRepository $repo_user)
    {
        $this->repo_user = $repo_user;
    }

    public function list()
    {
        $users = $this->repo_user->list($this->filters, $this->sorters);

        return response()->json($users);
    }

    public function get(Int $id)
    {
        $user = $this->repo_user->get($id);

        return response()->json($user);
    }

    public function search(Request $request)
    {
        $rules =
        [
            'search' => [ 'required', 'string', 'min:4', 'max:64' ],
        ];

        $attributes = $this->validate($request, $rules);

        $users = $this->repo_user->search($attributes, $this->sorters);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $rules =
        [
            'first_name'    => [ 'required', 'string', 'min:6', 'max:64' ],
            'last_name'     => [ 'required', 'string', 'min:6', 'max:64' ],
            'email'         => [ 'required', 'email', 'unique:users', 'min:6', 'max:128' ],
            'password'      => [ 'required', 'confirmed', 'string', 'min:6', 'max:16' ],
        ];

        $attributes = $this->validate($request, $rules);

        $user = $this->repo_user->create($attributes);

        return response()->json($user);
    }

    public function update(Request $request, Int $id)
    {
        $rules =
        [
            'first_name'    => [ 'required', 'string', 'min:6', 'max:64' ],
            'last_name'     => [ 'required', 'string', 'min:6', 'max:64' ],
            'email'         => [ 'required', 'email', 'unique:users', 'min:6', 'max:128' ],
            'password'      => [ 'required', 'confirmed', 'string', 'min:6', 'max:16' ],
            'meta'          => [ 'nullable', 'array' ],
            'meta.locale'   => [ 'nullable', 'string', 'enum' ],
            // TODO
        ];

        $attributes = $this->validate($request, $rules);

        $user = $this->repo_user->update($id, $attributes);

        return response()->json($user);
    }

    public function delete(Int $id)
    {
        $user = $this->repo_user->delete($id);

        return response()->json($user);
    }
}
