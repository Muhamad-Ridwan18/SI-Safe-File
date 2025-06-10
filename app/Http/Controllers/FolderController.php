<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FolderController extends Controller
{
    public function index(Request $request)
{
    $query = Folder::whereNull('parent_id');
    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    
    $folders = $query->get();
    $categories = Category::all();
    return view('folders.index', compact('folders', 'categories'));
}
    

    public function show(Request $request, $id)
    {
        $folder = Folder::with('children', 'documents')->findOrFail($id);
        $categories = Category::pluck('name', 'id');
        if ($folder->password) {
            $accessedFolders = Session::get('accessed_folders', []);
            
            if (!in_array($folder->id, $accessedFolders)) {
                return view('folders.password', compact('folder'));
            }
        }
        
        return view('folders.show', compact('folder', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:folders,id',
            'password' => 'nullable|string|min:4',
        ]);        

        Folder::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->user()->id,
            'password' => $request->filled('password') ? Hash::make($request->password) : null,
        ]);

        return redirect()->back()->with('success', 'Folder berhasil dibuat.');
    }

    public function access(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!\Hash::check($request->password, $folder->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        $accessedFolders = Session::get('accessed_folders', []);
        if (!in_array($folder->id, $accessedFolders)) {
            $accessedFolders[] = $folder->id;
            Session::put('accessed_folders', $accessedFolders);
        }

        return redirect()->route('folder.show', $folder->id)->with('success', 'Password benar, mengakses folder.');
    }

    public function edit(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $categories = Category::pluck('name', 'id');
        return view('folders.edit', compact('folder', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $folder->update($request->all());
        return redirect()->route('folder.show', $folder->id)->with('success', 'Folder berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();
        return redirect()->back()->with('success', 'Folder berhasil dihapus.');
    }
}