<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard() 
    {
        $user = Auth::user();
        $isAdmin = $user->role == 'Admin';

        // Query builder for user scoping
        $documentQuery = Document::with(['user', 'category', 'folder']);
        $documentByCategoryQuery = Document::select('category_id')->with('category');
        $documentByUserQuery = Document::select('user_id')->with('user');
        $recentActivityQuery = Document::with(['user', 'category']);
        $monthlyUploads = [];

        if (!$isAdmin) {
            $documentQuery->where('user_id', $user->id);
            $documentByCategoryQuery->where('user_id', $user->id);
            $documentByUserQuery->where('user_id', $user->id);
            $recentActivityQuery->where('user_id', $user->id);
        }

        $documents = $documentQuery->latest()->take(10)->get();
        $totalDocuments = $isAdmin ? Document::count() : Document::where('user_id', $user->id)->count();
        $totalCategories = $isAdmin ? Category::count() : Category::whereHas('documents', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalFolders = $isAdmin ? Folder::count() : Folder::whereHas('documents', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalUsers = $isAdmin ? User::count() : 1;

        $documentsByCategory = $documentByCategoryQuery
            ->get()
            ->groupBy('category.name')
            ->map(function ($group) {
                return $group->count();
            });

        $documentsByUser = $documentByUserQuery
            ->get()
            ->groupBy('user.name')
            ->map(function ($group) {
                return $group->count();
            });

        $recentActivity = $recentActivityQuery
            ->latest()
            ->take(8)
            ->get();

        $topCategories = Category::withCount(['documents' => function($q) use ($isAdmin, $user) {
                if (!$isAdmin) {
                    $q->where('user_id', $user->id);
                }
            }])
            ->orderBy('documents_count', 'desc')
            ->take(5)
            ->get();

        $activeUsers = User::withCount(['documents' => function($q) use ($isAdmin, $user) {
                if (!$isAdmin) {
                    $q->where('user_id', $user->id);
                }
            }])
            ->orderBy('documents_count', 'desc')
            ->take(5)
            ->get();

        // Monthly upload statistics (last 6 months)
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyCountQuery = Document::whereYear('created_at', $date->year)
                                         ->whereMonth('created_at', $date->month);
            if (!$isAdmin) {
                $monthlyCountQuery->where('user_id', $user->id);
            }
            $count = $monthlyCountQuery->count();
            $monthlyUploads[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        return view('welcome', compact(
            'documents',
            'totalDocuments', 
            'totalCategories', 
            'totalFolders', 
            'totalUsers',
            'documentsByCategory',
            'documentsByUser',
            'recentActivity',
            'topCategories',
            'activeUsers',
            'monthlyUploads'
        ));
    }
    
}
