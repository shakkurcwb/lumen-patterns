<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\UserModel;
use App\Models\UserMetaModel;

class UserRepository
{
    protected $userModel, $userMetaModel;

    public function __construct(UserModel $userModel, UserMetaModel $userMetaModel)
    {
        $this->userModel = $userModel;
        $this->userMetaModel = $userMetaModel;
    }

    /**
     * List all users.
     *
     * @param  Array  $filters
     * @param  Array  $sorters
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function list(Array $filters = [], Array $sorters = []):Collection
    {
        $users = $this->userModel;

        // Apply filters
        foreach($filters as $field => $value)
        {
            $users = $users->where($field, $value);
        }

        // Apply sorters
        foreach($sorters as $field => $order)
        {
            $users = $users->orderBy($field, $order);
        }

        return $users->get();
    }

    /**
     * Get a user.
     *
     * @param  Int     $key
     * @param  String  $field
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function get(Int $key, String $field = 'id'):Collection
    {
        $user = $this->userModel;

        // Find user by key
        $user = $user->where($field, $key);

        return $user->get();
    }

    /**
     * Search for users.
     *
     * @param  Array  $attributes
     * @param  Array  $sorters
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function search(Array $attributes, Array $sorters = []):Collection
    {
        $user = $this->userModel;
        $userMeta = $this->userMetaModel;

        $userFields = $user->getFillable();
        $metaFields = $userMeta->getFillable();

        // Search in user fields
        foreach($userFields as $field)
        {
            $user = $user->orWhere($field, 'LIKE', '%'.$attributes['search'].'%');
        }

        // Search in user meta fields
        foreach($metaFields as $field)
        {
            $user = $user->whereHas('meta', function($query) use ($field, $attributes) {

                $query->orWhere($field, 'LIKE', '%'.$attributes['search'].'%');
            });
        }

        return $user->get();
    }

    /**
     * Create a user.
     *
     * @param  Array  $attributes
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function create(Array $attributes):Collection
    {
        // Create a user
        $user = $this->userModel->create($attributes);

        // Create associated meta relationship
        $user->meta()->create([]);

        return $this->get($user->id);
    }

    /**
     * Update a user.
     *
     * @param  Int    $id
     * @param  Array  $attributes
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function update(Int $id, Array $attributes):Collection
    {
        // Update a user
        $user = $this->userModel->update($id, $attributes);

        // Update associated meta relationship
        $user->meta()->update($attributes['meta']);

        return $this->get($user->id);
    }

    /**
     * Delete a user.
     *
     * @param  Int  $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function delete(Int $id):Collection
    {
        // Delete a user
        $this->userModel->delete($id);

        return $this->get($id);
    }
}