<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * 会員の一覧を表示する。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = User::search($search);

        $users = $query->paginate($maxRecords);

        return view('admin.users.index', compact('users', 'search'));
    }
}
