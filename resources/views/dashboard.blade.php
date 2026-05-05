@extends('base')

@section('title', 'Tableau de bord')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-chart-pie text-[#255156] mr-2"></i>
            Tableau de bord
        </h1>
        <div class="flex items-center gap-2">
            <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">{{ auth()->user()->role }}</span>
            <div class="w-8 h-8 rounded-full bg-[#255156] flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
    <!-- STATISTIQUES RAPIDES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Utilisateurs</div>
                    <div class="text-xl font-bold text-[#255156]">{{ $totalUsers ?? 0 }}</div>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-sm"></i>
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-1">{{ $validatedUsers ?? 0 }} validés • {{ $pendingUsers ?? 0 }} en attente</div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Structures</div>
                    <div class="text-xl font-bold text-[#255156]">{{ $totalStructures ?? 0 }}</div>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-sm"></i>
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-1">{{ $typesCount ?? 0 }} types • {{ $villesCount ?? 0 }} villes</div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Documents</div>
                    <div class="text-xl font-bold text-[#255156]">{{ $totalDocuments ?? 0 }}</div>
                </div>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-purple-600 text-sm"></i>
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-1">
                <span class="text-purple-600">{{ $stats['images'] ?? 0 }} images</span> • 
                <span class="text-red-600">{{ $stats['videos'] ?? 0 }} vidéos</span> • 
                <span class="text-blue-600">{{ $stats['documents'] ?? 0 }} docs</span>
            </div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Connexions</div>
                    <div class="text-xl font-bold text-[#255156]">{{ $totalConnexions ?? 0 }}</div>
                </div>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-yellow-600 text-sm"></i>
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-1">{{ $connexionsJour ?? 0 }} aujourd'hui</div>
        </div>

        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Téléchargements</div>
                    <div class="text-xl font-bold text-[#255156]">{{ $totalDownloads ?? 0 }}</div>
                </div>
                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-download text-pink-600 text-sm"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- GRAPHIQUES PRINCIPAUX -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Graphique utilisateurs par rôle -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-users text-[#255156] mr-2"></i>
                Utilisateurs par rôle
            </h3>
            <div style="height: 250px;">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Graphique structures par type -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-[#255156] mr-2"></i>
                Structures par type
            </h3>
            <div style="height: 250px;">
                <canvas id="structuresTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUES DOCUMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Graphique documents par type de fichier -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-file-alt text-[#255156] mr-2"></i>
                Documents par type
            </h3>
            <div style="height: 250px;">
                <canvas id="documentsTypeChart"></canvas>
            </div>
        </div>

        <!-- Graphique documents par catégorie -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-tags text-[#255156] mr-2"></i>
                Documents par catégorie
            </h3>
            <div style="height: 250px;">
                <canvas id="documentsCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ACTIVITÉ DES LOGS (admin seulement) -->
    @if(auth()->user()->role === 'admin')
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-chart-line text-[#255156] mr-2"></i>
                    Activité des 7 derniers jours
                </h3>
                <a href="{{ route('activity_logs.index') }}" class="text-xs text-[#255156] hover:underline">
                    Voir tous les logs →
                </a>
            </div>
            <div style="height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        @endif

    <!-- DERNIERS ÉLÉMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Derniers utilisateurs -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-user-plus text-[#255156] mr-2"></i>
                Derniers inscrits
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentUsers ?? [] as $user)
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                        <div class="w-6 h-6 bg-[#255156] rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 text-xs">
                            <span class="font-medium">{{ $user->prenom ?? '' }} {{ $user->name ?? '' }}</span>
                            <span class="text-gray-500 block">{{ $user->email ?? '' }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $user->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center py-4">Aucun utilisateur récent</p>
                @endforelse
            </div>
        </div>

        <!-- Derniers documents -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-file-upload text-[#255156] mr-2"></i>
                Derniers documents
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentDocuments ?? [] as $doc)
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                        <div class="w-6 h-6 
                            @if(in_array($doc->file_type, ['jpg','jpeg','png','gif','webp','svg'])) bg-purple-100 text-purple-600
                            @elseif(in_array($doc->file_type, ['mp4','webm','avi','mov','mkv'])) bg-red-100 text-red-600
                            @else bg-blue-100 text-blue-600
                            @endif rounded flex items-center justify-center">
                            <i class="fas {{ $doc->file_icon }} text-xs"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <span class="font-medium">{{ $doc->title }}</span>
                            <span class="text-gray-500 block">{{ Str::limit($doc->description ?? '', 30) }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $doc->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center py-4">Aucun document récent</p>
                @endforelse
            </div>
        </div>

        <!-- Derniers logs -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-history text-[#255156] mr-2"></i>
                Dernières activités
            </h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($recentLogs ?? [] as $log)
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                        <div class="w-6 h-6 
                            @if($log->action === 'login') bg-green-100 text-green-600
                            @elseif($log->action === 'create') bg-blue-100 text-blue-600
                            @elseif($log->action === 'update') bg-yellow-100 text-yellow-600
                            @elseif($log->action === 'delete') bg-red-100 text-red-600
                            @else bg-gray-100 text-gray-600
                            @endif rounded flex items-center justify-center">
                            <i class="fas 
                                @if($log->action === 'login') fa-sign-in-alt
                                @elseif($log->action === 'create') fa-plus
                                @elseif($log->action === 'update') fa-edit
                                @elseif($log->action === 'delete') fa-trash
                                @else fa-history
                                @endif text-xs"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <span class="font-medium">{{ $log->user->name ?? 'Système' }}</span>
                            <span class="text-gray-500 block">{{ Str::limit($log->description ?? '', 30) }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $log->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center py-4">Aucune activité récente</p>
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
    // ===== GRAPHIQUE UTILISATEURS =====
    const usersCtx = document.getElementById('usersChart')?.getContext('2d');
    if(usersCtx) {
        new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Administrateurs', 'Modérateurs', 'Utilisateurs'],
                datasets: [{
                    data: [{{ $admins ?? 0 }}, {{ $moderateurs ?? 0 }}, {{ $usersCount ?? 0 }}],
                    backgroundColor: ['#255156', '#8bbdc3', '#e5e7eb'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // ===== GRAPHIQUE STRUCTURES PAR TYPE =====
    const structuresTypeCtx = document.getElementById('structuresTypeChart')?.getContext('2d');
    if(structuresTypeCtx) {
        new Chart(structuresTypeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($typeLabels ?? []) !!},
                datasets: [{
                    label: 'Nombre de structures',
                    data: {!! json_encode($typeData ?? []) !!},
                    backgroundColor: '#255156',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
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
                labels: ['Images', 'Vidéos', 'Documents'],
                datasets: [{
                    data: [{{ $stats['images'] ?? 0 }}, {{ $stats['videos'] ?? 0 }}, {{ $stats['documents'] ?? 0 }}],
                    backgroundColor: ['#8b5cf6', '#ef4444', '#3b82f6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // ===== GRAPHIQUE DOCUMENTS PAR CATÉGORIE =====
    const docCatCtx = document.getElementById('documentsCategoryChart')?.getContext('2d');
    if(docCatCtx) {
        new Chart(docCatCtx, {
            type: 'bar',
            data: {
                labels: ['Procédures', 'Outils', 'Fiches réflexes', 'Ressources'],
                datasets: [{
                    label: 'Nombre de documents',
                    data: [
                        {{ $stats['categories']['procedure'] ?? 0 }},
                        {{ $stats['categories']['outil'] ?? 0 }},
                        {{ $stats['categories']['fiche_reflexe'] ?? 0 }},
                        {{ $stats['categories']['ressource'] ?? 0 }}
                    ],
                    backgroundColor: ['#255156', '#8bbdc3', '#f59e0b', '#10b981'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    }

    // ===== GRAPHIQUE ACTIVITÉ (admin) =====
    @if(auth()->user()->role === 'admin')
    const activityCtx = document.getElementById('activityChart')?.getContext('2d');
    if(activityCtx) {
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activityLabels) !!},
                datasets: [
                    {
                        label: 'Connexions',
                        data: {!! json_encode($activityConnexions) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#10b981'
                    },
                    {
                        label: 'Créations',
                        data: {!! json_encode($activityCreations) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Modifications',
                        data: {!! json_encode($activityUpdates) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#f59e0b'
                    },
                    {
                        label: 'Suppressions',
                        data: {!! json_encode($activityDeletes) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#ef4444'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            precision: 0,
                            callback: function(value) {
                                return value + ' act.';
                            }
                        }
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

/* Animation */
.bg-white {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.bg-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}
</style>
@endsection