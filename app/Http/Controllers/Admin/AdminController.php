<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * 管理者の一覧を表示する。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = Admin::search($search);

        $admins = $query->orderBy('id', 'desc')->paginate($maxRecords);

        return view('admin.admins.index', compact('admins', 'search'));
    }
}
