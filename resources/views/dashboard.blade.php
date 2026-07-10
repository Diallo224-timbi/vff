@extends('base')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-10xl mx-auto px-0 sm:px-6 lg:px-4 py-2 space-y-2">
    <!-- message de succès -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="rounded-2xl p-4 shadow-xl text-white d-flex flex-wrap justify-content-between align-items-center gap-3"
         style="background: linear-gradient(135deg, #255156, #1e7c86);">
        
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-xl" style="background: rgba(255,255,255,0.15);">
                <i class="fas fa-chart-pie" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="font-size: 1.1rem;">Tableau de bord</h5>
                <small class="text-white/80">
                    <i class="fas fa-info-circle me-1"></i>
                    Vue d'ensemble de la plateforme
                </small>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="background: rgba(255,255,255,0.15); color: white;">
                <i class="fas fa-user-shield me-1"></i>
                {{ auth()->user()->role }}
            </span>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm" 
                 style="background: linear-gradient(135deg, #4a8599, #255156);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- STATISTIQUES RAPIDES -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
        <div class="bg-white rounded-xl border p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
             style="border-color: #e8f3f2;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium" style="color: #4a7a7f;">Utilisateurs</p>
                    <p class="text-2xl font-bold" style="color: #255156;">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #e8f3f2;">
                    <i class="fas fa-users" style="color: #255156;"></i>
                </div>
            </div>
            <div class="mt-1 flex gap-2 text-[10px]" style="color: #7fa8ac;">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full" style="background: #10b981;"></span>
                    {{ $validatedUsers ?? 0 }} validés
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full" style="background: #f59e0b;"></span>
                    {{ $pendingUsers ?? 0 }} en attente
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
             style="border-color: #e8f3f2;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium" style="color: #4a7a7f;">Structures</p>
                    <p class="text-2xl font-bold" style="color: #255156;">{{ $totalStructures ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #e8f3f2;">
                    <i class="fas fa-building" style="color: #255156;"></i>
                </div>
            </div>
            <div class="mt-1 flex gap-2 text-[10px]" style="color: #7fa8ac;">
                <span>{{ $villesCount ?? 0 }} villes</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
             style="border-color: #e8f3f2;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium" style="color: #4a7a7f;">Organismes</p>
                    <p class="text-2xl font-bold" style="color: #255156;">{{ $organismes->count() ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #e8f3f2;">
                    <i class="fas fa-building" style="color: #255156;"></i>
                </div>
            </div>
            <div class="mt-1 flex gap-2 text-[10px]" style="color: #7fa8ac;">
                <span>Total organismes</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
             style="border-color: #e8f3f2;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium" style="color: #4a7a7f;">Connexions</p>
                    <p class="text-2xl font-bold" style="color: #255156;">{{ $totalConnexions ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #e8f3f2;">
                    <i class="fas fa-sign-in-alt" style="color: #255156;"></i>
                </div>
            </div>
            <div class="mt-1 flex gap-2 text-[10px]" style="color: #7fa8ac;">
                <span>{{ $connexionsJour ?? 0 }} aujourd'hui</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
             style="border-color: #e8f3f2;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium" style="color: #4a7a7f;">Documents</p>
                    <p class="text-2xl font-bold" style="color: #255156;">{{ $totalDocuments ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #e8f3f2;">
                    <i class="fas fa-file-alt" style="color: #255156;"></i>
                </div>
            </div>
            <div class="mt-1 flex gap-2 text-[10px]" style="color: #7fa8ac;">
                <span class="flex items-center gap-1">
                    <i class="fas fa-image" style="color: #8b5cf6;"></i>
                    {{ $stats['images'] ?? 0 }}
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-file-pdf" style="color: #3b82f6;"></i>
                    {{ $stats['documents'] ?? 0 }}
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-link" style="color: #0361f8;"></i>
                    {{ $stats['liens'] ?? 0 }}
                </span>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUES PRINCIPAUX -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Graphique utilisateurs par rôle -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-4 flex items-center" style="color: #255156;">
                <i class="fas fa-users mr-2"></i>
                Utilisateurs par rôle
            </h3>
            <div style="height: 280px;">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Graphique Organismes par nombre de structures (barres verticales) -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-4 flex items-center" style="color: #255156;">
                <i class="fas fa-building mr-2"></i>
                Organismes par nombre de structures
            </h3>
            <div style="height: 280px;">
                <canvas id="organismesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUES DOCUMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Graphique documents par type -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-4 flex items-center" style="color: #255156;">
                <i class="fas fa-file-alt mr-2"></i>
                Documents par type
            </h3>
            <div style="height: 250px;">
                <canvas id="documentsTypeChart"></canvas>
            </div>
        </div>
        <!-- Graphique documents par catégorie -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-4 flex items-center" style="color: #255156;">
                <i class="fas fa-tags mr-2"></i>
                Documents par catégorie
            </h3>
            <div style="height: 250px;">
                <canvas id="documentsCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ACTIVITÉ DES LOGS -->
    @if(auth()->user()->role === 'admin')
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold flex items-center" style="color: #255156;">
                    <i class="fas fa-chart-line mr-2"></i>
                    Activité des 7 derniers jours
                </h3>
                <a href="{{ route('activity_logs.index') }}" class="text-xs hover:underline" style="color: #4a8599;">
                    Voir tous les logs →
                </a>
            </div>
            <div style="height: 280px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    @endif

    <!-- DERNIERS ÉLÉMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Derniers utilisateurs -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-3 flex items-center" style="color: #255156;">
                <i class="fas fa-user-plus mr-2"></i>
                Derniers inscrits
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentUsers ?? [] as $user)
                    <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold"
                             style="background: linear-gradient(135deg, #255156, #4a8599);">
                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium truncate" style="color: #1a3c40;">
                                {{ $user->prenom ?? '' }} {{ $user->name ?? '' }}
                            </p>
                            <p class="text-[10px] truncate" style="color: #7fa8ac;">{{ $user->email ?? '' }}</p>
                        </div>
                        <span class="text-[10px] whitespace-nowrap" style="color: #b0c8cb;">{{ $user->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-center py-4" style="color: #b0c8cb;">Aucun utilisateur récent</p>
                @endforelse
            </div>
        </div>

        <!-- Derniers documents -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-3 flex items-center" style="color: #255156;">
                <i class="fas fa-file-upload mr-2"></i>
                Derniers documents
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentDocuments ?? [] as $doc)
                    <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center
                            @if(in_array($doc->file_type, ['jpg','jpeg','png','gif','webp','svg'])) bg-purple-100 text-purple-600
                            @elseif(in_array($doc->file_type, ['mp4','webm','avi','mov','mkv'])) bg-red-100 text-red-600
                            @else bg-blue-100 text-blue-600
                            @endif">
                            <i class="fas {{ $doc->file_icon }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium truncate" style="color: #1a3c40;">{{ $doc->title }}</p>
                            <p class="text-[10px] truncate" style="color: #7fa8ac;">{{ Str::limit($doc->description ?? '', 30) }}</p>
                        </div>
                        <span class="text-[10px] whitespace-nowrap" style="color: #b0c8cb;">{{ $doc->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-center py-4" style="color: #b0c8cb;">Aucun document récent</p>
                @endforelse
            </div>
        </div>

        <!-- Derniers logs -->
        <div class="bg-white rounded-xl border p-4" style="border-color: #e8f3f2;">
            <h3 class="text-sm font-semibold mb-3 flex items-center" style="color: #255156;">
                <i class="fas fa-history mr-2"></i>
                Dernières activités
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentLogs ?? [] as $log)
                    <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center
                            @if($log->action === 'login') bg-green-100 text-green-600
                            @elseif($log->action === 'create') bg-blue-100 text-blue-600
                            @elseif($log->action === 'update') bg-yellow-100 text-yellow-600
                            @elseif($log->action === 'delete') bg-red-100 text-red-600
                            @else bg-gray-100 text-gray-600
                            @endif">
                            <i class="fas 
                                @if($log->action === 'login') fa-sign-in-alt
                                @elseif($log->action === 'create') fa-plus
                                @elseif($log->action === 'update') fa-edit
                                @elseif($log->action === 'delete') fa-trash
                                @else fa-history
                                @endif text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium truncate" style="color: #1a3c40;">{{ $log->user->prenom ?? 'Système' }}</p>
                            <p class="text-[10px] truncate" style="color: #7fa8ac;">{{ Str::limit($log->description ?? '', 30) }}</p>
                        </div>
                        <span class="text-[10px] whitespace-nowrap" style="color: #b0c8cb;">{{ $log->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-center py-4" style="color: #b0c8cb;">Aucune activité récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== PALETTE DE COULEURS =====
    const colors = {
        primary: '#255156',
        secondary: '#4a8599',
        tertiary: '#8bbdc3',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#3b82f6',
        purple: '#8b5cf6',
        gray: '#e5e7eb',
        light: '#f8fcfc'
    };

    // ===== GRAPHIQUE UTILISATEURS PAR RÔLE =====
    const usersCtx = document.getElementById('usersChart')?.getContext('2d');
    if(usersCtx) {
        new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Administrateurs', 'Responsables Organismes', 'Responsables Structures', 'Utilisateurs'],
                datasets: [{
                    data: [{{ $admins ?? 0 }}, {{ $moderateurs ?? 0 }}, {{ $moderateur_classique ?? 0 }}, {{ $usersCount ?? 0 }}],
                    backgroundColor: [colors.primary, colors.secondary, colors.success, colors.tertiary],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 10 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // ===== GRAPHIQUE ORGANISMES PAR NOMBRE DE STRUCTURES (BARRES VERTICALES) =====
    const organismesCtx = document.getElementById('organismesChart')?.getContext('2d');
    if(organismesCtx) {
        const organismesLabels = {!! json_encode($organismes->pluck('nom_organisme')->toArray() ?? []) !!};
        const organismesData = {!! json_encode($organismeStructures ?? []) !!};
        
        const colorPalette = [
            '#255156', '#4a8599', '#8bbdc3', '#10b981', '#f59e0b', 
            '#3b82f6', '#8b5cf6', '#ef4444', '#ec4899', '#14b8a6',
            '#f97316', '#6366f1', '#06b6d4', '#84cc16', '#a855f7'
        ];
        
        const backgroundColors = organismesLabels.map((_, i) => colorPalette[i % colorPalette.length]);
        
        new Chart(organismesCtx, {
            type: 'bar',
            data: {
                labels: organismesLabels.length ? organismesLabels : ['Aucun organisme'],
                datasets: [{
                    label: 'Nombre de structures',
                    data: organismesData.length ? organismesData : [0],
                    backgroundColor: backgroundColors,
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#1a3c40',
                        bodyColor: '#255156',
                        borderColor: '#e8f3f2',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return value + ' structure' + (value > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            precision: 0,
                            font: { size: 10 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { size: 9 },
                            maxRotation: 30,
                            autoSkip: true,
                            maxTicksLimit: 15
                        }
                    }
                }
            }
        });
    }

    // ===== GRAPHIQUE DOCUMENTS PAR TYPE =====
    const docTypeCtx = document.getElementById('documentsTypeChart')?.getContext('2d');
    if(docTypeCtx) {
        new Chart(docTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Images', 'Documents', 'Liens'],
                datasets: [{
                    data: [{{ $stats['images'] ?? 0 }}, {{ $stats['documents'] ?? 0 }}, {{ $stats['liens'] ?? 0 }}],
                    backgroundColor: [colors.purple, colors.primary, colors.success],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 10 }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // ===== GRAPHIQUE DOCUMENTS PAR CATÉGORIE =====
    const docCatCtx = document.getElementById('documentsCategoryChart')?.getContext('2d');
    if(docCatCtx) {
        const catLabels = ['Procédures', 'Outils', 'Fiches réflexes', 'Ressources'];
        const catData = [
            {{ $stats['categories']['procedure'] ?? 0 }},
            {{ $stats['categories']['outil'] ?? 0 }},
            {{ $stats['categories']['fiche_reflexe'] ?? 0 }},
            {{ $stats['categories']['ressource'] ?? 0 }}
        ];
        const catColors = [colors.primary, colors.secondary, colors.warning, colors.success];
        
        new Chart(docCatCtx, {
            type: 'bar',
            data: {
                labels: catLabels,
                datasets: [{
                    label: 'Nombre de documents',
                    data: catData,
                    backgroundColor: catColors,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#1a3c40',
                        bodyColor: '#255156',
                        borderColor: '#e8f3f2',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            precision: 0,
                            font: { size: 10 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // ===== GRAPHIQUE ACTIVITÉ (admin) =====
    @if(auth()->user()->role === 'admin')
    const activityCtx = document.getElementById('activityChart')?.getContext('2d');
    if(activityCtx) {
        const gradient1 = activityCtx.createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradient1.addColorStop(1, 'rgba(16, 185, 129, 0.02)');
        
        const gradient2 = activityCtx.createLinearGradient(0, 0, 0, 300);
        gradient2.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient2.addColorStop(1, 'rgba(59, 130, 246, 0.02)');
        
        const gradient3 = activityCtx.createLinearGradient(0, 0, 0, 300);
        gradient3.addColorStop(0, 'rgba(245, 158, 11, 0.3)');
        gradient3.addColorStop(1, 'rgba(245, 158, 11, 0.02)');
        
        const gradient4 = activityCtx.createLinearGradient(0, 0, 0, 300);
        gradient4.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
        gradient4.addColorStop(1, 'rgba(239, 68, 68, 0.02)');

        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activityLabels) !!},
                datasets: [
                    {
                        label: 'Connexions',
                        data: {!! json_encode($activityConnexions) !!},
                        borderColor: colors.success,
                        backgroundColor: gradient1,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.success,
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Créations',
                        data: {!! json_encode($activityCreations) !!},
                        borderColor: colors.info,
                        backgroundColor: gradient2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.info,
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Modifications',
                        data: {!! json_encode($activityUpdates) !!},
                        borderColor: colors.warning,
                        backgroundColor: gradient3,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.warning,
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Suppressions',
                        data: {!! json_encode($activityDeletes) !!},
                        borderColor: colors.danger,
                        backgroundColor: gradient4,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.danger,
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            boxWidth: 12,
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 9 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#1a3c40',
                        bodyColor: '#255156',
                        borderColor: '#e8f3f2',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            precision: 0,
                            font: { size: 10 },
                            callback: function(value) {
                                return value + ' act.';
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }
    @endif
});
</script>

<style>
/* Scrollbar */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 2px;
}

/* Cartes */
.bg-white {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Animation des graphiques */
canvas {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hauteur des graphiques */
#organismesChart, #usersChart, #activityChart {
    max-height: 280px;
}
</style>
@endsection