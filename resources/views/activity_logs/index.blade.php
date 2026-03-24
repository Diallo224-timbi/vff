@extends('base')

@section('title', 'Logs d\'activité')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Messages de succès -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-sm"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- En-tête -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-history text-[#255156] mr-2"></i>
            Logs d'activité
        </h1>
        <div class="flex gap-2">
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition-colors" id="refreshStats">
                <i class="fas fa-sync-alt mr-1"></i>
                Actualiser
            </button>
            <button class="bg-[#255156] hover:bg-[#1d4144] text-white px-3 py-2 rounded-lg text-sm transition-colors" id="quickStats">
                <i class="fas fa-chart-line mr-1"></i>
                Statistiques
            </button>
            <!-- supprimer tous les logs (admin seulement) -->
            @if(auth()->user()->role === 'admin')
                <form action="{{ route('activity_logs.destroyAll') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer tous les logs ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Supprimer tous les logs
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Mini statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="text-xs text-gray-500 mb-1">Total logs</div>
            <div class="text-xl font-semibold text-[#255156]" id="totalLogs">{{ $totalLogs ?? $logs->total() }}</div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="text-xs text-gray-500 mb-1">Actions uniques</div>
            <div class="text-xl font-semibold text-green-600" id="uniqueActions">{{ $uniqueActions ?? $logs->pluck('action')->unique()->count() }}</div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="text-xs text-gray-500 mb-1">Utilisateurs actifs</div>
            <div class="text-xl font-semibold text-blue-600" id="activeUsers">{{ $activeUsers ?? $logs->pluck('user_id')->unique()->filter()->count() }}</div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="text-xs text-gray-500 mb-1">Dernière activité</div>
            <div class="text-sm font-medium text-gray-700" id="lastActivity">
                @if($logs->isNotEmpty())
                    {{ $logs->first()->created_at->diffForHumans() }}
                @else
                    Aucune
                @endif
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 mb-4">
        <form method="GET" action="{{ route('activity_logs.index') }}" class="flex flex-wrap gap-2" id="filterForm">
            <select name="user_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#255156]">
                <option value="">Tous utilisateurs</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            
            <select name="action" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#255156]">
                <option value="">Toutes actions</option>
                @foreach($actionTypes as $actionType)
                    <option value="{{ $actionType }}" {{ request('action') == $actionType ? 'selected' : '' }}>
                        {{ $actionType }}
                    </option>
                @endforeach
            </select>
            
            <input type="date" name="date_start" value="{{ request('date_start') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#255156]">
            
            <input type="date" name="date_end" value="{{ request('date_end') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#255156]">
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherche..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#255156] flex-1 min-w-[200px]">
            
            <button type="submit" class="bg-[#255156] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#1d4144] transition-colors">
                <i class="fas fa-search mr-1"></i>Filtrer
            </button>
            
            <a href="{{ route('activity_logs.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                <i class="fas fa-undo mr-1"></i>Reset
            </a>
        </form>
    </div>

    <!-- Table des logs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">ID</th>
                        <th class="px-4 py-3 text-left font-medium">Utilisateur</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                        <th class="px-4 py-3 text-left font-medium">Description</th>
                        <th class="px-4 py-3 text-left font-medium">IP</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-600">#{{ $log->id }}</td>
                            <td class="px-4 py-2">
                                @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-[#255156] text-white rounded-full flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $log->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">Système</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $colors = [
                                        'create' => 'text-green-700 bg-green-50',
                                        'update' => 'text-blue-700 bg-blue-50',
                                        'delete' => 'text-red-700 bg-red-50',
                                        'login' => 'text-purple-700 bg-purple-50',
                                        'logout' => 'text-yellow-700 bg-yellow-50',
                                    ];
                                    $color = $colors[$log->action] ?? 'text-gray-700 bg-gray-50';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-600 max-w-xs truncate" title="{{ $log->description }}">
                                {{ Str::limit($log->description, 50) }}
                            </td>
                            <td class="px-4 py-2">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $log->ip_address }}</code>
                            </td>
                            <td class="px-4 py-2 text-gray-600">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Bouton Voir détails -->
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors view-details" 
                                            data-log-id="{{ $log->id }}" 
                                            data-log='@json($log)'
                                            title="Voir les détails complets">
                                        <i class="bx bx-show text-xs mr-1"></i>
                                        
                                    </button>
                                    
                                    <!-- Bouton Supprimer (admin seulement) -->
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('activity_logs.destroy', $log->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce log ?')"
                                                    title="Supprimer définitivement">
                                                <i class="bx bx-trash text-xs mr-1"></i>
                                               
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                                Aucun log trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 flex justify-center">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modal Statistiques -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="statsModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] overflow-hidden">
        <div class="bg-[#255156] text-white px-4 py-3 flex justify-between items-center">
            <h3 class="font-semibold">
                <i class="fas fa-chart-line mr-2"></i>
                Statistiques des logs
            </h3>
            <button onclick="closeStatsModal()" class="text-white hover:text-gray-200 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <!-- Sélecteurs de période -->
            <div class="flex gap-2 mb-4">
                <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors period-btn active bg-[#255156] text-white" data-period="week">
                    <i class="fas fa-calendar-week mr-1"></i>Semaine
                </button>
                <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors period-btn bg-gray-100 text-gray-700 hover:bg-gray-200" data-period="month">
                    <i class="fas fa-calendar-alt mr-1"></i>Mois
                </button>
                <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors period-btn bg-gray-100 text-gray-700 hover:bg-gray-200" data-period="year">
                    <i class="fas fa-calendar mr-1"></i>Année
                </button>
            </div>
            
            <div class="mb-2 text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Basé sur vos <strong>{{ $logs->total() }}</strong> logs enregistrés
            </div>
            
            <!-- Graphique -->
            <div style="height: 350px;">
                <canvas id="statsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="logDetailsModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="bg-[#255156] text-white px-4 py-3 flex justify-between items-center">
            <h3 class="font-semibold">
                <i class="fas fa-info-circle mr-2"></i>
                Détails du log
            </h3>
            <button onclick="closeLogDetailsModal()" class="text-white hover:text-gray-200 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4" id="logDetailsContent">
            <!-- Le contenu sera rempli dynamiquement -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fermeture des modales
    function closeStatsModal() {
        document.getElementById('statsModal').classList.add('hidden');
        document.getElementById('statsModal').classList.remove('flex');
    }
    
    function closeLogDetailsModal() {
        document.getElementById('logDetailsModal').classList.add('hidden');
        document.getElementById('logDetailsModal').classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Données réelles des logs
        const logsData = @json($logs->items());
        
        // Actualisation stats
        document.getElementById('refreshStats')?.addEventListener('click', function() {
            location.reload();
        });

        // Graphique
        let chart = null;
        
        // Ouvrir le modal avec la vue semaine par défaut
        document.getElementById('quickStats')?.addEventListener('click', function() {
            document.getElementById('statsModal').classList.remove('hidden');
            document.getElementById('statsModal').classList.add('flex');
            loadWeekData();
        });

        // Sélecteurs de période
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Mise à jour des classes
                document.querySelectorAll('.period-btn').forEach(b => {
                    b.classList.remove('active', 'bg-[#255156]', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                });
                this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                this.classList.add('active', 'bg-[#255156]', 'text-white');
                
                // Charger les données selon la période
                const period = this.dataset.period;
                if(period === 'week') loadWeekData();
                else if(period === 'month') loadMonthData();
                else if(period === 'year') loadYearData();
            });
        });

        // Analyse hebdomadaire (7 derniers jours)
        function loadWeekData() {
            const ctx = document.getElementById('statsChart')?.getContext('2d');
            if(!ctx) return;
            
            if(chart) chart.destroy();

            const labels = [];
            const data = [];
            
            for(let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];
                
                // Compter les logs pour cette date
                const count = logsData.filter(log => {
                    const logDate = new Date(log.created_at).toISOString().split('T')[0];
                    return logDate === dateStr;
                }).length;
                
                labels.push(date.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric' }));
                data.push(count);
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de logs',
                        data: data,
                        backgroundColor: '#255156',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Activité des 7 derniers jours',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });
        }

        // Analyse mensuelle (30 derniers jours)
        function loadMonthData() {
            const ctx = document.getElementById('statsChart')?.getContext('2d');
            if(!ctx) return;
            
            if(chart) chart.destroy();

            const labels = [];
            const data = [];
            
            // Grouper par jour sur 30 jours
            const dailyCounts = {};
            
            for(let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];
                dailyCounts[dateStr] = 0;
            }
            
            // Compter les logs par jour
            logsData.forEach(log => {
                const logDate = new Date(log.created_at).toISOString().split('T')[0];
                if(dailyCounts.hasOwnProperty(logDate)) {
                    dailyCounts[logDate]++;
                }
            });
            
            // Créer les labels et données
            Object.keys(dailyCounts).forEach((dateStr, index) => {
                const date = new Date(dateStr);
                // Afficher seulement quelques dates pour éviter la surcharge
                if(index % 3 === 0 || index === 29) {
                    labels.push(date.getDate() + '/' + (date.getMonth() + 1));
                } else {
                    labels.push('');
                }
                data.push(dailyCounts[dateStr]);
            });

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de logs',
                        data: data,
                        backgroundColor: '#255156',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Activité des 30 derniers jours',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });
        }

        // Analyse annuelle (12 derniers mois)
        function loadYearData() {
            const ctx = document.getElementById('statsChart')?.getContext('2d');
            if(!ctx) return;
            
            if(chart) chart.destroy();

            const mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                         'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            const shortMois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 
                              'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            
            const monthlyCounts = {};
            
            // Initialiser les 12 derniers mois
            for(let i = 11; i >= 0; i--) {
                const date = new Date();
                date.setMonth(date.getMonth() - i);
                const key = date.getMonth() + '-' + date.getFullYear();
                monthlyCounts[key] = {
                    count: 0,
                    label: shortMois[date.getMonth()] + ' ' + date.getFullYear(),
                    month: date.getMonth(),
                    year: date.getFullYear()
                };
            }
            
            // Compter les logs par mois
            logsData.forEach(log => {
                const logDate = new Date(log.created_at);
                const key = logDate.getMonth() + '-' + logDate.getFullYear();
                if(monthlyCounts[key]) {
                    monthlyCounts[key].count++;
                }
            });
            
            // Créer les labels et données dans l'ordre chronologique
            const labels = [];
            const data = [];
            
            Object.keys(monthlyCounts).sort().forEach(key => {
                labels.push(monthlyCounts[key].label);
                data.push(monthlyCounts[key].count);
            });

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de logs',
                        data: data,
                        backgroundColor: '#255156',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Activité des 12 derniers mois',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });
        }

        // Détails du log
        document.querySelectorAll('.view-details').forEach(btn => {
            btn.addEventListener('click', function() {
                const logData = JSON.parse(this.dataset.log);
                const modal = document.getElementById('logDetailsModal');
                const content = document.getElementById('logDetailsContent');
                
                const date = new Date(logData.created_at).toLocaleString('fr-FR');
                const user = logData.user ? logData.user.name : 'Système';
                const userEmail = logData.user ? logData.user.email : '-';
                
                content.innerHTML = `
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">ID:</span>
                            <span class="col-span-2">#${logData.id}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">Utilisateur:</span>
                            <span class="col-span-2">${user} (${userEmail})</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">Action:</span>
                            <span class="col-span-2"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">${logData.action}</span></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">Description:</span>
                            <span class="col-span-2">${logData.description || '-'}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">IP Adresse:</span>
                            <span class="col-span-2"><code class="bg-gray-200 px-2 py-1 rounded">${logData.ip_address}</code></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">Date:</span>
                            <span class="col-span-2">${date}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-600">User Agent:</span>
                            <span class="col-span-2 text-xs break-all">${logData.user_agent || '-'}</span>
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        // Fermeture avec Échap
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeStatsModal();
                closeLogDetailsModal();
            }
        });

        // Fermeture en cliquant en dehors
        document.getElementById('statsModal').addEventListener('click', function(e) {
            if(e.target === this) closeStatsModal();
        });
        
        document.getElementById('logDetailsModal').addEventListener('click', function(e) {
            if(e.target === this) closeLogDetailsModal();
        });
    });
</script>
@endsection