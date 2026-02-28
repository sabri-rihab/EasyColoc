<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:50',
            'icon' => 'nullable|string|max:5' // Typically an emoji
        ]);

        Category::create([
            'colocation_id' => $colocation->id,
            'name' => $request->name,
            'icon' => $request->icon ?? '💰',
        ]);

        return back()->with('success', "Catégorie « {$request->name} » ajoutée !");
    }
}
