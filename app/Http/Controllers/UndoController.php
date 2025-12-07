<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UndoController extends Controller
{
    public function undoController()
    {
        undo();

        // Redirect kembali atau tampilkan pesan
        return redirect()->back()->with('success', 'Undo berhasil dilakukan.');
    }
}
