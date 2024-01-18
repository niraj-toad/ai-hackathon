<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FullNameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('name', new FullNameFilter()),
                'email',
            ])
            ->allowedSorts(['first_name', 'last_name', 'email', 'created_at'])
            ->defaultSort('created_at');
        $users = $query->paginate($request->per_page ?? 15, page: $request->page ?? 1);

        return UserResource::collection($users);
    }
}
