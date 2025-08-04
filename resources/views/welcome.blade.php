@extends('layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row">
                    <!-- Welcome Card with Animation -->
                    <div class="col-12 mb-3">
                        <div class="card welcome-card shadow-lg"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-white mb-1">Selamat datang kembali, <b>{{ Auth::user()->name }}</b>!
                                            👋</h3>
                                        <p class="text-white-75 mb-0">Berikut adalah ringkasan aktivitas dokumen Anda hari
                                            ini.</p>
                                    </div>
                                    <div class="welcome-icon">
                                        <i data-feather="file-text" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Summary Cards -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card stats-card h-100 shadow-sm border-0"
                            style="border-left: 4px solid #28c76f !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Dokumen</h6>
                                        <h2 class="fw-bolder mb-0 counter" data-target="{{ $totalDocuments }}">0</h2>
                                        <small class="text-success">
                                            <i data-feather="trending-up" class="me-1"
                                                style="width: 14px; height: 14px;"></i>
                                            Aktif
                                        </small>
                                    </div>
                                    <div class="stats-icon">
                                        <i data-feather="file" style="width: 32px; height: 32px; color: #28c76f;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card stats-card h-100 shadow-sm border-0"
                            style="border-left: 4px solid #7367f0 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Kategori</h6>
                                        <h2 class="fw-bolder mb-0 counter" data-target="{{ $totalCategories }}">0</h2>
                                        <small class="text-primary">
                                            <i data-feather="tag" class="me-1" style="width: 14px; height: 14px;"></i>
                                            Tersedia
                                        </small>
                                    </div>
                                    <div class="stats-icon">
                                        <i data-feather="bookmark" style="width: 32px; height: 32px; color: #7367f0;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card stats-card h-100 shadow-sm border-0"
                            style="border-left: 4px solid #ff9f43 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Folder</h6>
                                        <h2 class="fw-bolder mb-0 counter" data-target="{{ $totalFolders }}">0</h2>
                                        <small class="text-warning">
                                            <i data-feather="folder" class="me-1" style="width: 14px; height: 14px;"></i>
                                            Terorganisir
                                        </small>
                                    </div>
                                    <div class="stats-icon">
                                        <i data-feather="folder-plus"
                                            style="width: 32px; height: 32px; color: #ff9f43;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card stats-card h-100 shadow-sm border-0"
                            style="border-left: 4px solid #ea5455 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Pengguna</h6>
                                        <h2 class="fw-bolder mb-0 counter" data-target="{{ $totalUsers }}">0</h2>
                                        <small class="text-danger">
                                            <i data-feather="users" class="me-1" style="width: 14px; height: 14px;"></i>
                                            Terdaftar
                                        </small>
                                    </div>
                                    <div class="stats-icon">
                                        <i data-feather="user-check" style="width: 32px; height: 32px; color: #ea5455;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="col-lg-12 col-12 mt-3">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Statistik Upload (6 Bulan Terakhir)</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mt-3">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Aktivitas Terbaru</h4>
                                <button class="btn btn-primary btn-sm" onclick="refreshActivity()">
                                    <i data-feather="refresh-cw" style="width: 16px; height: 16px;"></i>
                                    Refresh
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="timeline" id="recentActivityList">
                                    @forelse($recentActivity as $activity)
                                        <div class="timeline-item">
                                            <div class="timeline-point timeline-point-primary">
                                                <i data-feather="upload" style="width: 14px; height: 14px;"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">{{ $activity->original_filename }}</h6>
                                                <p class="mb-1">
                                                    Diupload oleh <strong>{{ $activity->user->name }}</strong>
                                                    @if ($activity->category)
                                                        ke kategori <span
                                                            class="badge badge-light-primary">{{ $activity->category->name }}</span>
                                                    @endif
                                                </p>
                                                <small
                                                    class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">Belum ada aktivitas.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mt-3">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Pengguna Teraktif</h4>
                                    </div>
                                    <div class="card-body">
                                        @forelse($activeUsers as $user)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <div class="avatar-content bg-primary text-white">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->documents_count }}
                                                            dokumen</small>
                                                    </div>
                                                </div>
                                                <div class="badge badge-light-primary">
                                                    {{ $user->documents_count }}
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted text-center">Belum ada pengguna aktif.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Kategori</h4>
                                    </div>
                                    <div class="card-body">
                                        @forelse($topCategories as $category)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="category-icon me-3">
                                                        <i data-feather="tag"
                                                            style="width: 18px; height: 18px; color: #7367f0;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                                        <small class="text-muted">{{ $category->documents_count }}
                                                            dokumen</small>
                                                    </div>
                                                </div>
                                                <div class="progress" style="width: 100px; height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ ($category->documents_count / $totalDocuments) * 100 }}%"
                                                        aria-valuenow="{{ $category->documents_count }}"
                                                        aria-valuemin="0" aria-valuemax="{{ $totalDocuments }}"></div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted text-center">Belum ada kategori.</p>
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Enhanced Recent Documents Table -->
                    <div class="col-12 mt-3">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Dokumen Terbaru</h4>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" style="width: auto;"
                                        onchange="filterDocuments(this.value)">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($topCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="documentsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <i data-feather="file" style="width: 16px; height: 16px;"
                                                        class="me-1"></i>
                                                    Nama File
                                                </th>
                                                <th>
                                                    <i data-feather="tag" style="width: 16px; height: 16px;"
                                                        class="me-1"></i>
                                                    Kategori
                                                </th>
                                                <th>
                                                    <i data-feather="folder" style="width: 16px; height: 16px;"
                                                        class="me-1"></i>
                                                    Folder
                                                </th>
                                                <th>
                                                    <i data-feather="user" style="width: 16px; height: 16px;"
                                                        class="me-1"></i>
                                                    Pengguna
                                                </th>
                                                <th>
                                                    <i data-feather="calendar" style="width: 16px; height: 16px;"
                                                        class="me-1"></i>
                                                    Diupload
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($documents as $doc)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i data-feather="file-text"
                                                                style="width: 20px; height: 20px; color: #7367f0;"
                                                                class="me-2"></i>
                                                            <div>
                                                                <span
                                                                    class="fw-medium">{{ $doc->original_filename }}</span>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ number_format(strlen($doc->original_filename) * 1024) }}
                                                                    bytes</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($doc->category)
                                                            <span
                                                                class="badge badge-light-primary">{{ $doc->category->name }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($doc->folder)
                                                            <span
                                                                class="badge badge-light-warning">{{ $doc->folder->name }}</span>
                                                        @else
                                                            <span class="text-muted">Root</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-xs me-2">
                                                                <div class="avatar-content bg-primary text-white">
                                                                    {{ strtoupper(substr($doc->user->name, 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            {{ $doc->user->name }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            {{ $doc->created_at->format('d M Y') }}
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $doc->created_at->format('H:i') }}</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <i data-feather="inbox"
                                                            style="width: 48px; height: 48px; color: #ddd;"
                                                            class="mb-2"></i>
                                                        <br>
                                                        <span class="text-muted">Belum ada dokumen.</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- /.row -->
            </div> <!-- /.content-body -->
        </div> <!-- /.content-wrapper -->
    </div>

    <!-- Custom Styles -->
    <style>
        .welcome-card {
            border-radius: 15px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .welcome-card:hover {
            transform: translateY(-5px);
        }

        .stats-card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e3e3;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-point {
            position: absolute;
            left: -25px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-point-primary {
            background: #7367f0;
            color: white;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-xs {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }

        .avatar-sm {
            width: 28px;
            height: 28px;
            font-size: 14px;
        }

        .avatar-content {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .category-icon,
        .stats-icon {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            opacity: 1;
        }

        .table th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #7367f0, #9c88ff);
        }
    </style>

    <!-- Enhanced JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Counter Animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                let current = 0;
                const increment = target / 50;

                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 30);
                    } else {
                        counter.textContent = target;
                    }
                };

                updateCounter();
            });

            // Monthly Chart
            const monthlyData = @json($monthlyUploads);
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(item => item.month),
                        datasets: [{
                            label: 'Dokumen Diupload',
                            data: monthlyData.map(item => item.count),
                            borderColor: '#7367f0',
                            backgroundColor: 'rgba(115, 103, 240, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#7367f0',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Category Doughnut Chart
            const categoryData = @json($documentsByCategory);
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx && Object.keys(categoryData).length > 0) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(categoryData),
                        datasets: [{
                            data: Object.values(categoryData),
                            backgroundColor: [
                                '#7367f0',
                                '#28c76f',
                                '#ff9f43',
                                '#ea5455',
                                '#00cfe8',
                                '#6f42c1'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            }
                        }
                    }
                });
            }
        });

        // Interactive Functions
        function refreshActivity() {
            const button = event.target.closest('button');
            const icon = button.querySelector('[data-feather="refresh-cw"]');

            // Add spinning animation
            icon.style.animation = 'spin 1s linear infinite';

            // Simulate refresh
            setTimeout(() => {
                icon.style.animation = '';
                // Here you would typically make an AJAX call to refresh data
                showToast('Aktivitas berhasil diperbarui!', 'success');
            }, 1500);
        }

        function filterDocuments(category) {
            const table = document.getElementById('documentsTable');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (category === '') {
                    row.style.display = '';
                } else {
                    const categoryCell = row.cells[1];
                    const categoryText = categoryCell.textContent.trim();

                    if (categoryText.includes(category)) {
                        row.style.display = '';
                        row.style.animation = 'fadeIn 0.5s ease';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function exportDocuments() {
            showToast('Export sedang diproses...', 'info');
            // Here you would implement actual export functionality
            setTimeout(() => {
                showToast('Dokumen berhasil diekspor!', 'success');
            }, 2000);
        }

        function showUploadModal() {
            showToast('Modal upload akan dibuka...', 'info');
            // Here you would show upload modal
        }

        function showCreateFolderModal() {
            showToast('Modal folder akan dibuka...', 'info');
            // Here you would show create folder modal
        }

        function showCreateCategoryModal() {
            showToast('Modal kategori akan dibuka...', 'info');
            // Here you would show create category modal
        }

        function showToast(message, type = 'info') {
            // Create toast notification
            const toastContainer = document.getElementById('toast-container') || createToastContainer();

            const toast = document.createElement('div');
            toast.className = `toast toast-${type} show`;
            toast.innerHTML = `
        <div class="toast-header">
            <i data-feather="${getToastIcon(type)}" style="width: 16px; height: 16px;" class="me-2"></i>
            <strong class="me-auto">Notifikasi</strong>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

            toastContainer.appendChild(toast);

            // Initialize feather icons for the new toast
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }

        function getToastIcon(type) {
            const icons = {
                'success': 'check-circle',
                'error': 'x-circle',
                'warning': 'alert-triangle',
                'info': 'info'
            };
            return icons[type] || 'info';
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .toast {
        margin-bottom: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
        animation: slideInRight 0.3s ease;
    }
    
    .toast-success {
        background: linear-gradient(45deg, #28c76f, #48da89);
        color: white;
    }
    
    .toast-error {
        background: linear-gradient(45deg, #ea5455, #ff6b6b);
        color: white;
    }
    
    .toast-warning {
        background: linear-gradient(45deg, #ff9f43, #ffb976);
        color: white;
    }
    
    .toast-info {
        background: linear-gradient(45deg, #00cfe8, #26d0e6);
        color: white;
    }
    
    .toast-header {
        background: transparent;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        color: inherit;
    }
    
    .toast .btn-close {
        filter: invert(1);
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(115, 103, 240, 0.05);
        transform: scale(1.01);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        border-radius: 10px;
        padding: 10px 0;
    }
    
    .dropdown-item {
        padding: 8px 20px;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background: linear-gradient(45deg, #7367f0, #9c88ff);
        color: white;
        transform: translateX(3px);
    }
    
    .progress {
        background: rgba(115, 103, 240, 0.1);
    }
    
    .welcome-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .stats-card {
        overflow: hidden;
        position: relative;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .stats-card:hover::before {
        left: 100%;
    }
    
    .timeline-content {
        background: rgba(115, 103, 240, 0.02);
        padding: 15px;
        border-radius: 10px;
        border-left: 3px solid #7367f0;
        margin-left: 10px;
    }
    
    .badge {
        position: relative;
        overflow: hidden;
    }
    
    .badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.3s;
    }
    
    .badge:hover::before {
        left: 100%;
    }
`;
        document.head.appendChild(style);

        // Initialize tooltips and other Bootstrap components
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading states to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('no-loading')) {
                        const originalText = this.innerHTML;
                        this.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                        this.disabled = true;

                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                            if (typeof feather !== 'undefined') {
                                feather.replace();
                            }
                        }, 1000);
                    }
                });
            });

            // Add real-time clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                // You can add this to somewhere in your header if needed
                const clockElement = document.getElementById('live-clock');
                if (clockElement) {
                    clockElement.textContent = timeString;
                }
            }

            // Update clock every second
            setInterval(updateClock, 1000);
            updateClock();

            // Add search functionality to tables
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control form-control-sm';
            searchInput.placeholder = 'Cari dokumen...';
            searchInput.style.width = '200px';

            // Add search input to table header if needed
            const tableHeader = document.querySelector('#documentsTable').closest('.card-header');
            if (tableHeader) {
                const searchContainer = document.createElement('div');
                searchContainer.className = 'd-flex align-items-center';
                searchContainer.innerHTML =
                    '<i data-feather="search" style="width: 16px; height: 16px;" class="me-2"></i>';
                searchContainer.appendChild(searchInput);

                // Insert search before existing buttons
                const existingButtons = tableHeader.querySelector('.d-flex.gap-2');
                if (existingButtons) {
                    existingButtons.parentNode.insertBefore(searchContainer, existingButtons);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            }

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#documentsTable tbody tr');

                tableRows.forEach(row => {
                    const fileName = row.cells[0].textContent.toLowerCase();
                    const category = row.cells[1].textContent.toLowerCase();
                    const folder = row.cells[2].textContent.toLowerCase();
                    const user = row.cells[3].textContent.toLowerCase();

                    if (fileName.includes(searchTerm) ||
                        category.includes(searchTerm) ||
                        folder.includes(searchTerm) ||
                        user.includes(searchTerm)) {
                        row.style.display = '';
                        row.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + K for search
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[placeholder="Cari dokumen..."]');
                if (searchInput) {
                    searchInput.focus();
                    showToast('Gunakan pencarian untuk menemukan dokumen', 'info');
                }
            }

            // Ctrl + U for upload
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                showUploadModal();
            }

            // Ctrl + N for new folder
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                showCreateFolderModal();
            }
        });

        // Welcome message based on time
        const hour = new Date().getHours();
        let greeting = '';
        if (hour < 12) {
            greeting = 'Selamat pagi';
        } else if (hour < 17) {
            greeting = 'Selamat siang';
        } else {
            greeting = 'Selamat malam';
        }

        // Update welcome message if element exists
        const welcomeElement = document.querySelector('.welcome-card h3');
        if (welcomeElement) {
            const userName = welcomeElement.textContent.match(/\*\*(.*?)\*\*/);
            if (userName) {
                welcomeElement.innerHTML = `${greeting}, <b>${userName[1]}</b>! 👋`;
            }
        }

        console.log('Dashboard loaded successfully! 🚀');
        console.log('Keyboard shortcuts:');
        console.log('- Ctrl + K: Search documents');
        console.log('- Ctrl + U: Upload document');
        console.log('- Ctrl + N: Create new folder');
    </script>
@endsection
