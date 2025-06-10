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
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        
        $folders = $query->get();
        $categories = Category::all();
        return view('folders.index', compact('folders', 'categories'));
    }
    
    public function show(Request $request, $id)
    {
        $folder = Folder::with('children', 'documents')->findOrFail($id);
        $categories = Category::all();
        
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
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
            'category_id' => 'nullable|exists:categories,id',
            'password' => 'nullable|string|min:4',
        ]);        

        Folder::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
            'password' => $request->filled('password') ? Hash::make($request->password) : null,
        ]);

        // Redirect based on context
        if ($request->parent_id) {
            return redirect()->route('folder.show', $request->parent_id)->with('success', 'Folder berhasil dibuat.');
        }
        
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dibuat.');
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

        // Store accessed folder in session
        $accessedFolders = Session::get('accessed_folders', []);
        if (!in_array($folder->id, $accessedFolders)) {
            $accessedFolders[] = $folder->id;
            Session::put('accessed_folders', $accessedFolders);
        }

        return redirect()->route('folder.show', $folder->id)->with('success', 'Password benar, mengakses folder.');
    }

    public function edit($id)
    {
        $folder = Folder::findOrFail($id);
        $categories = Category::all();
        
        // Check if folder has password and user has access
        if ($folder->password) {
            $accessedFolders = Session::get('accessed_folders', []);
            if (!in_array($folder->id, $accessedFolders)) {
                return redirect()->route('folder.show', $folder->id)
                    ->withErrors(['access' => 'Anda perlu mengakses folder terlebih dahulu.']);
            }
        }
        
        return view('folders.edit', compact('folder', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'password' => 'nullable|string|min:4',
            'remove_password' => 'nullable|boolean',
        ]);

        // Update folder data
        $updateData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
        ];

        // Handle password update
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        } elseif ($request->has('remove_password') && $request->remove_password) {
            $updateData['password'] = null;
        }

        $folder->update($updateData);

        return redirect()->route('folder.show', $folder->id)->with('success', 'Folder berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $folder = Folder::with('children', 'documents')->findOrFail($id);
        
        // Check if folder has children or documents
        if ($folder->children->count() > 0 || $folder->documents->count() > 0) {
            return redirect()->back()->withErrors(['delete' => 'Folder tidak dapat dihapus karena masih berisi subfolder atau dokumen.']);
        }
        
        $parentId = $folder->parent_id;
        $folder->delete();
        
        // Redirect based on context
        if ($parentId) {
            return redirect()->route('folder.show', $parentId)->with('success', 'Folder berhasil dihapus.');
        }
        
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dihapus.');
    }
}