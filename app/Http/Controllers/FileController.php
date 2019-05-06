<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * フォームを表示
     */
    public function showCreateForm()
    {
        return view('files.create');
    }
}
