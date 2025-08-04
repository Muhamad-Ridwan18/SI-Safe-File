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
        // Existing data
        $documents = Document::with(['user', 'category', 'folder'])->latest()->take(10)->get();
        $totalDocuments = Document::count();
        $totalCategories = Category::count();
        $totalFolders = Folder::count();
        $totalUsers = User::count();
        
        // Additional data for enhanced dashboard
        $documentsByCategory = Document::select('category_id')
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($group) {
                return $group->count();
            });
        
        $documentsByUser = Document::select('user_id')
            ->with('user')
            ->get()
            ->groupBy('user.name')
            ->map(function ($group) {
                return $group->count();
            });
        
        $recentActivity = Document::with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();
        
        $topCategories = Category::withCount('documents')
            ->orderBy('documents_count', 'desc')
            ->take(5)
            ->get();
        
        $activeUsers = User::withCount('documents')
            ->orderBy('documents_count', 'desc')
            ->take(5)
            ->get();
        
        // Monthly upload statistics (last 6 months)
        $monthlyUploads = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Document::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
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
