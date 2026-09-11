{{-- resources/views/schemas/index.blade.php --}}
@extends('base')
@section('title', 'Schéma violences - Gestion des GT')
@section('content')

<div class="container-fluid px-4 mt-3">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #145f68;">
                        <i class="fas fa-project-diagram me-2"></i>Schéma violences
                    </h4>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Gestion des groupes de travail et comptes rendus</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="openCreateSchemaModal()" class="btn btn-sm" style="background: #145f68; color: white; padding: 4px 12px; font-size: 0.8rem;">
                        <i class="fas fa-plus me-1"></i> Nouveau CR
                    </button>
                    <a href="{{ route('resources.index') }}" class="btn btn-sm btn-outline-secondary" style="padding: 4px 10px; font-size: 0.8rem;">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>

            <!-- ============ MESSAGES DE SUCCÈS / ERREUR ============ -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3 py-2" role="alert" style="border-left: 5px solid #28a745; border-radius: 10px; background: #f0fff4; font-size: 0.85rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-lg me-3" style="color: #28a745;"></i>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: #155724;">✅ Succès !</h6>
                            <p class="mb-0" style="color: #155724; font-size: 0.8rem;">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3 py-2" role="alert" style="border-left: 5px solid #dc3545; border-radius: 10px; background: #fff5f5; font-size: 0.85rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-lg me-3" style="color: #dc3545;"></i>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: #721c24;">❌ Erreur !</h6>
                            <p class="mb-0" style="color: #721c24; font-size: 0.8rem;">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3 py-2" role="alert" style="border-left: 5px solid #dc3545; border-radius: 10px; background: #fff5f5; font-size: 0.85rem;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-circle fa-lg me-3 mt-1" style="color: #dc3545;"></i>
                        <div>
                            <h6 class="mb-1 fw-bold" style="color: #721c24;">❌ Veuillez corriger les erreurs :</h6>
                            <ul class="mb-0 ps-3" style="color: #721c24; font-size: 0.8rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- ============================================= -->

            <!-- Vue principale -->
            <div class="row g-3">
                <!-- Colonne de gauche : Arborescence -->
                <div class="col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header py-2" style="background: #145f68; color: white;">
                            <h6 class="mb-0"><i class="fas fa-sitemap me-2"></i>Groupes de travail</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 520px; overflow-y: auto;">
                            <div id="gtTree" class="p-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Colonne de droite : Contenu -->
                <div class="col-lg-8 col-xl-9">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <span id="currentGtTitle" class="fw-semibold" style="font-size: 0.95rem;">Sélectionnez un groupe</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button onclick="toggleView()" class="btn btn-sm btn-outline-secondary" id="toggleViewBtn" style="padding: 2px 8px; font-size: 0.75rem;">
                                    <i class="fas fa-th-large me-1"></i> Vue carte
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <!-- Barre d'outils -->
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                        <input type="text" id="schemaSearch" class="form-control" placeholder="Rechercher..." style="font-size: 0.8rem; height: 30px;">
                                    </div>
                                    <select id="schemaFilter" class="form-select form-select-sm" style="width: 120px; font-size: 0.8rem; height: 30px;">
                                        <option value="all">Tous les types</option>
                                        <option value="pdf">PDF</option>
                                        <option value="doc">Word</option>
                                        <option value="odt">ODT</option>
                                        <option value="rtf">RTF</option>
                                        <option value="txt">TXT</option>
                                    </select>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <span id="visibleSchemasCount">0</span> documents
                                </div>
                            </div>

                            <!-- Zone de contenu -->
                            <div id="schemasContent" style="min-height: 380px;">
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-hand-pointer fa-3x mb-3 opacity-25"></i>
                                    <p style="font-size: 0.95rem;">Sélectionnez un groupe de travail pour voir les comptes rendus</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DE CRÉATION                            -->
<!-- ============================================ -->
<div id="createSchemaModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header py-2" style="background: #145f68; color: white; border-radius: 12px 12px 0 0;">
                <h6 class="modal-title">
                    <i class="fas fa-plus me-2"></i> <span id="schemaModalTitle">Nouveau compte rendu</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createSchemaForm" method="POST" action="{{ route('schemas.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-2" style="font-size: 0.85rem;">
                    <div class="row">
                        <div class="col-md-6" id="categoryColumn">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1">Groupe de travail <span class="text-danger">*</span></label>
                                <select id="schemaCategory" name="category" required class="form-select form-select-sm" onchange="toggleSubCategory()">
                                    <option value="">Sélectionner</option>
                                    <option value="GT1">GT1 - Réseau VIF-VC</option>
                                    <option value="GT2">GT2 - Force/Justice/Santé</option>
                                    <option value="GT3">GT3 - Auteurs de violences</option>
                                    <option value="GT4">GT4 - Cellule familiale</option>
                                    <option value="GT5">GT5 - Hébergement - Logement</option>
                                    <option value="GT6">GT6 - Pilotage du schéma</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6" id="subCategoryColumn">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1">Sous-groupe</label>
                                <select id="schemaSubCategory" name="sub_category" class="form-select form-select-sm">
                                    <option value="">Aucun</option>
                                    <option value="SGT1">SGT1 - Sensibilisation & formations</option>
                                    <option value="SGT2">SGT2 - Coordination acteurs</option>
                                    <option value="SGT3">SGT3 - Outils professionnels</option>
                                    <option value="SGT4">SGT4 - Parcours</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-1">Titre du CR <span class="text-danger">*</span></label>
                        <input type="text" id="schemaTitle" name="title" required class="form-control form-control-sm" placeholder="Ex: CR réunion GT1 du 15/04/2025">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-1">Description</label>
                        <textarea id="schemaDescription" name="description" rows="2" class="form-control form-control-sm" placeholder="Résumé de la réunion..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-1">Fichier <span class="text-danger">*</span></label>
                        <input type="file" id="schemaFile" name="file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.odt,.rtf,.txt" required>
                        <small class="text-muted" style="font-size: 0.72rem;">Formats acceptés: PDF, DOC, DOCX, ODT, RTF, TXT - Max 20Mo</small>
                    </div>

                    <input type="hidden" id="schemaData" name="data" value='{"elements":[],"appState":[],"files":[]}'>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm" style="background: #145f68; color: white;">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DE VISUALISATION                       -->
<!-- ============================================ -->
<div id="schemaViewModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header py-2" style="background: #145f68; color: white;">
                <h6 class="modal-title" id="viewModalTitle">Compte rendu</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2" id="viewModalBody"></div>
        </div>
    </div>
</div>

<style>
    /* ========================================== */
    /* STYLES GÉNÉRAUX                           */
    /* ========================================== */
    .gt-node {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.85rem;
    }
    .gt-node:hover {
        background: #f0f7f7;
    }
    .gt-node.active {
        background: #e8f4f5;
        border-left: 3px solid #145f68;
    }
    .gt-node .badge {
        font-size: 0.65rem;
    }
    .gt-children {
        margin-left: 12px;
        border-left: 2px solid #e5e7eb;
        padding-left: 10px;
    }
    
    .schema-card {
        transition: all 0.2s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    .schema-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    .schema-card .card-body {
        padding: 0.6rem !important;
    }
    .schema-card .card-footer {
        background: transparent;
        border-top: 1px solid #f0f0f0;
        padding: 0.3rem 0.6rem 0.4rem 0.6rem !important;
    }
    .schema-card .card-title,
    .schema-card h6 {
        font-size: 0.82rem !important;
        line-height: 1.2;
    }
    .schema-card p {
        font-size: 0.72rem;
        line-height: 1.2;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 24px;
        padding-bottom: 12px;
        border-left: 2px solid #e5e7eb;
    }
    .timeline-item:last-child {
        border-left: none;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #145f68;
    }
    
    .alert {
        animation: slideDown 0.5s ease;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #subCategoryColumn {
        transition: all 0.3s ease;
    }
    #subCategoryColumn.hidden {
        display: none;
    }
    #categoryColumn {
        transition: all 0.3s ease;
    }
    #categoryColumn.full-width {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    /* ========================================== */
    /* ICÔNES POUR LES TYPES DE FICHIERS          */
    /* ========================================== */
    .file-icon-pdf { color: #dc3545; }
    .file-icon-word { color: #0d6efd; }
    .file-icon-odt { color: #6f42c1; }
    .file-icon-rtf { color: #fd7e14; }
    .file-icon-txt { color: #6c757d; }
    .file-icon-other { color: #6c757d; }
    
    .badge-pdf { background: #fee2e2; color: #dc3545; }
    .badge-word { background: #dbeafe; color: #0d6efd; }
    .badge-odt { background: #e9d5ff; color: #6f42c1; }
    .badge-rtf { background: #ffedd5; color: #fd7e14; }
    .badge-txt { background: #f3f4f6; color: #6c757d; }
    .badge-other { background: #f3f4f6; color: #6c757d; }
    
    /* Réduction des icônes de fichiers */
    .file-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.4rem;
    }
    .file-icon-box-sm {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
    
    /* ============================================
       COMPACTAGE POUR 1920x1080 @ 125% (~1536x864 CSS)
       ============================================ */
    .form-select-sm,
    .form-control-sm,
    .input-group-sm > .form-control,
    .input-group-sm > .input-group-text {
        height: 30px !important;
        padding: 2px 8px !important;
        font-size: 0.8rem !important;
    }

    .btn-sm {
        padding: 3px 8px !important;
        font-size: 0.75rem !important;
    }
    
    @media (max-width: 768px) {
        .gt-children {
            margin-left: 8px;
            padding-left: 8px;
        }
    }

    /* Ciblage précis 1920x1080 @ 125% */
    @media screen and (min-width: 1500px) and (max-width: 1600px)
                  and (min-height: 850px) and (max-height: 900px) {

        #schemaFilter,
        #schemaSearch {
            height: 28px !important;
        }

        .card-body.p-2 {
            padding: 0.5rem !important;
        }

        .schema-card .card-body {
            padding: 0.5rem !important;
        }

        .gt-node {
            padding: 4px 8px;
            font-size: 0.82rem;
        }
    }
</style>

<script>
// ============================================ //
// GESTION DE L'AFFICHAGE DE LA SOUS-CATÉGORIE  //
// ============================================ //

function toggleSubCategory() {
    const category = document.getElementById('schemaCategory').value;
    const subCategoryCol = document.getElementById('subCategoryColumn');
    const categoryCol = document.getElementById('categoryColumn');
    
    if (category === 'GT1') {
        subCategoryCol.classList.remove('hidden');
        categoryCol.classList.remove('full-width');
        categoryCol.className = 'col-md-6';
        subCategoryCol.className = 'col-md-6';
        document.getElementById('schemaSubCategory').removeAttribute('required');
    } else {
        subCategoryCol.classList.add('hidden');
        categoryCol.classList.add('full-width');
        categoryCol.className = 'col-md-12 full-width';
        document.getElementById('schemaSubCategory').value = '';
        document.getElementById('schemaSubCategory').removeAttribute('required');
    }
}

// ============================================ //
// GESTION DES SCHÉMAS                          //
// ============================================ //

class SchemaManager {
    constructor() {
        this.schemas = @json($schemas ?? []);
        this.currentGt = null;
        this.currentView = 'grid';
        this.filteredSchemas = [];
        this.gtStructure = {
            'GT1': {
                label: 'GT1 - Réseau VIF-VC',
                icon: 'fa-network-wired',
                children: ['SGT1', 'SGT2', 'SGT3', 'SGT4']
            },
            'GT2': {
                label: 'GT2 - Force de l\'ordre, justice et santé',
                icon: 'fa-gavel',
                children: []
            },
            'GT3': {
                label: 'GT3 - Auteurs de violences',
                icon: 'fa-user-slash',
                children: []
            },
            'GT4': {
                label: 'GT4 - Cellule familiale',
                icon: 'fa-users',
                children: []
            },
            'GT5': {
                label: 'GT5 - Hébergement - Logement',
                icon: 'fa-home',
                children: []
            },
            'GT6': {
                label: 'GT6 - Pilotage du schéma',
                icon: 'fa-chart-line',
                children: []
            }
        };
        this.sgtLabels = {
            'SGT1': 'SGT1 - Sensibilisation & formations',
            'SGT2': 'SGT2 - Coordination acteurs',
            'SGT3': 'SGT3 - Outils professionnels',
            'SGT4': 'SGT4 - Parcours'
        };
        this.init();
    }

    init() {
        this.renderTree();
        this.updateCounts();
        this.setupEventListeners();
        this.selectDefaultGt();
        setTimeout(toggleSubCategory, 100);
    }

    renderTree() {
        const container = document.getElementById('gtTree');
        let html = '<div class="list-group list-group-flush">';
        
        Object.keys(this.gtStructure).forEach((gtKey) => {
            const gt = this.gtStructure[gtKey];
            const count = this.getSchemaCount(gtKey);
            const hasChildren = gt.children && gt.children.length > 0;
            
            html += `
                <div class="list-group-item border-0 p-0">
                    <div class="gt-node d-flex align-items-center justify-content-between" 
                         data-gt="${gtKey}"
                         onclick="schemaManager.selectGt('${gtKey}')">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas ${gt.icon} text-primary"></i>
                            <span>${gt.label}</span>
                        </div>
                        <span class="badge bg-secondary">${count}</span>
                    </div>
                    ${hasChildren ? `
                        <div class="gt-children">
                            ${gt.children.map(childKey => `
                                <div class="gt-node d-flex align-items-center justify-content-between ps-3" 
                                     data-gt="${childKey}"
                                     style="font-size: 0.8rem;"
                                     onclick="schemaManager.selectGt('${childKey}')">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-chevron-right text-muted" style="font-size: 0.55rem;"></i>
                                        <span>${this.sgtLabels[childKey] || childKey}</span>
                                    </div>
                                    <span class="badge bg-info">${this.getSchemaCount(childKey)}</span>
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }

    getSchemaCount(gt) {
        return this.schemas.filter(s => s.category === gt || s.sub_category === gt).length;
    }

    updateCounts() {
        document.querySelectorAll('[data-gt]').forEach(el => {
            const gt = el.dataset.gt;
            const count = this.getSchemaCount(gt);
            const badge = el.querySelector('.badge');
            if (badge) badge.textContent = count;
        });
    }

    selectDefaultGt() {
        const firstGt = Object.keys(this.gtStructure)[0];
        this.selectGt(firstGt);
    }

    selectGt(gt) {
        this.currentGt = gt;
        
        document.querySelectorAll('[data-gt]').forEach(el => {
            el.classList.toggle('active', el.dataset.gt === gt);
        });
        
        const label = this.gtStructure[gt]?.label || this.sgtLabels[gt] || gt;
        document.getElementById('currentGtTitle').textContent = label;
        
        this.filteredSchemas = this.schemas.filter(s => 
            s.category === gt || s.sub_category === gt
        );
        
        this.renderSchemas(this.filteredSchemas);
        this.updateVisibleCount();
    }

    // ============================================ //
    // GESTION DES ICÔNES PAR TYPE DE FICHIER       //
    // ============================================ //
    getFileInfo(fileType) {
        if (!fileType) {
            return {
                icon: 'fa-file',
                iconClass: 'file-icon-other',
                badgeClass: 'badge-other',
                label: 'Fichier',
                color: '#6c757d',
                bg: '#f3f4f6'
            };
        }
        
        const type = fileType.toLowerCase();
        const icons = {
            'pdf': {
                icon: 'fa-file-pdf',
                iconClass: 'file-icon-pdf',
                badgeClass: 'badge-pdf',
                label: 'PDF',
                color: '#dc3545',
                bg: '#fee2e2'
            },
            'doc': {
                icon: 'fa-file-word',
                iconClass: 'file-icon-word',
                badgeClass: 'badge-word',
                label: 'DOC',
                color: '#0d6efd',
                bg: '#dbeafe'
            },
            'docx': {
                icon: 'fa-file-word',
                iconClass: 'file-icon-word',
                badgeClass: 'badge-word',
                label: 'DOCX',
                color: '#0d6efd',
                bg: '#dbeafe'
            },
            'odt': {
                icon: 'fa-file-alt',
                iconClass: 'file-icon-odt',
                badgeClass: 'badge-odt',
                label: 'ODT',
                color: '#6f42c1',
                bg: '#e9d5ff'
            },
            'rtf': {
                icon: 'fa-file-alt',
                iconClass: 'file-icon-rtf',
                badgeClass: 'badge-rtf',
                label: 'RTF',
                color: '#fd7e14',
                bg: '#ffedd5'
            },
            'txt': {
                icon: 'fa-file-alt',
                iconClass: 'file-icon-txt',
                badgeClass: 'badge-txt',
                label: 'TXT',
                color: '#6c757d',
                bg: '#f3f4f6'
            }
        };
        
        return icons[type] || {
            icon: 'fa-file',
            iconClass: 'file-icon-other',
            badgeClass: 'badge-other',
            label: type.toUpperCase(),
            color: '#6c757d',
            bg: '#f3f4f6'
        };
    }

    renderSchemas(schemas) {
        const container = document.getElementById('schemasContent');
        
        if (schemas.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class=""bx bxs-file-pdf fa-3x mb-3 opacity-25"></i>
                    <p style="font-size: 0.95rem;">Aucun compte rendu pour ce groupe</p>
                    <button onclick="openCreateSchemaModal('${this.currentGt}')" class="btn btn-sm" style="background: #145f68; color: white; padding: 4px 12px; font-size: 0.8rem;">
                        <i class="fas fa-plus me-1"></i> Ajouter un CR
                    </button>
                </div>
            `;
            return;
        }

        if (this.currentView === 'grid') {
            this.renderGridView(schemas);
        } else {
            this.renderListView(schemas);
        }
    }

    // ============================================ //
    // RENDU EN MODE GRILLE (CARTES)                //
    // ============================================ //
    renderGridView(schemas) {
        const container = document.getElementById('schemasContent');
        let html = '<div class="row g-2">';
        
        schemas.forEach(schema => {
            const fileInfo = this.getFileInfo(schema.file_type);
            
            html += `
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card schema-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-2">
                                <div class="file-icon-box" style="background: ${fileInfo.bg};">
                                    <i class="fas ${fileInfo.icon} ${fileInfo.iconClass}" style="color: ${fileInfo.color};"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">${this.escapeHtml(schema.title)}</h6>
                                    <div class="d-flex gap-1 flex-wrap mt-1">
                                        <span class="badge ${fileInfo.badgeClass}" style="font-size: 0.65rem;">
                                            <i class="fas ${fileInfo.icon} me-1"></i> ${fileInfo.label}
                                        </span>
                                        ${schema.category ? `<span class="badge bg-secondary" style="font-size: 0.65rem;">${schema.category}</span>` : ''}
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                                        <i class="fas fa-calendar me-1"></i> ${new Date(schema.created_at).toLocaleDateString('fr-FR')}
                                    </small>
                                </div>
                            </div>
                            ${schema.description ? `<p class="text-muted mt-2 mb-0">${this.escapeHtml(schema.description)}</p>` : ''}
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-1">
                            ${schema.file_path ? `
                                <a href="${'/storage/' + schema.file_path}" target="_blank" class="btn btn-sm btn-outline-primary" style="padding: 1px 6px; font-size: 0.65rem;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${'/storage/' + schema.file_path}" download class="btn btn-sm btn-outline-secondary" style="padding: 1px 6px; font-size: 0.65rem;">
                                    <i class="fas fa-download"></i>
                                </a>
                            ` : ''}
                            <button onclick="schemaManager.deleteSchema(${schema.id})" class="btn btn-sm btn-outline-danger" style="padding: 1px 6px; font-size: 0.65rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }

    // ============================================ //
    // RENDU EN MODE LISTE (TIMELINE)               //
    // ============================================ //
    renderListView(schemas) {
        const container = document.getElementById('schemasContent');
        let html = '<div class="timeline">';
        
        schemas.forEach((schema) => {
            const fileInfo = this.getFileInfo(schema.file_type);
            
            html += `
                <div class="timeline-item">
                    <div class="d-flex align-items-start gap-2">
                        <div class="file-icon-box-sm" style="background: ${fileInfo.bg};">
                            <i class="fas ${fileInfo.icon} ${fileInfo.iconClass}" style="color: ${fileInfo.color};"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div>
                                    <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">${this.escapeHtml(schema.title)}</h6>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        <i class="fas fa-calendar me-1"></i> ${new Date(schema.created_at).toLocaleDateString('fr-FR')}
                                        ${schema.user_name ? `• <i class="fas fa-user me-1"></i>${this.escapeHtml(schema.user_name)}` : ''}
                                        <span class="ms-2 badge ${fileInfo.badgeClass}" style="font-size: 0.65rem;">
                                            <i class="fas ${fileInfo.icon} me-1"></i> ${fileInfo.label}
                                        </span>
                                    </small>
                                </div>
                                <div class="d-flex gap-1">
                                    ${schema.file_path ? `
                                        <a href="${'/storage/' + schema.file_path}" target="_blank" class="btn btn-sm btn-outline-primary" style="padding: 1px 6px; font-size: 0.65rem;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="${'/storage/' + schema.file_path}" download class="btn btn-sm btn-outline-secondary" style="padding: 1px 6px; font-size: 0.65rem;">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    ` : ''}
                                    <button onclick="schemaManager.deleteSchema(${schema.id})" class="btn btn-sm btn-outline-danger" style="padding: 1px 6px; font-size: 0.65rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            ${schema.description ? `<p class="text-muted mt-1 mb-0" style="font-size: 0.72rem;">${this.escapeHtml(schema.description)}</p>` : ''}
                            <div class="d-flex gap-1 mt-1">
                                ${schema.category ? `<span class="badge bg-secondary" style="font-size: 0.65rem;">${schema.category}</span>` : ''}
                                ${schema.sub_category ? `<span class="badge bg-info" style="font-size: 0.65rem;">${schema.sub_category}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }

    toggleView() {
        this.currentView = this.currentView === 'grid' ? 'list' : 'grid';
        const btn = document.getElementById('toggleViewBtn');
        btn.innerHTML = this.currentView === 'grid' 
            ? '<i class="fas fa-th-large me-1"></i> Vue carte' 
            : '<i class="fas fa-list me-1"></i> Vue liste';
        this.renderSchemas(this.filteredSchemas);
    }

    updateVisibleCount() {
        document.getElementById('visibleSchemasCount').textContent = this.filteredSchemas.length;
    }

    setupEventListeners() {
        document.getElementById('schemaSearch')?.addEventListener('input', (e) => {
            this.searchSchemas(e.target.value);
        });

        document.getElementById('schemaFilter')?.addEventListener('change', (e) => {
            this.filterByType(e.target.value);
        });
    }

    searchSchemas(query) {
        const filtered = this.schemas.filter(s => {
            const searchTerm = query.toLowerCase();
            return (s.category === this.currentGt || s.sub_category === this.currentGt) &&
                   (s.title.toLowerCase().includes(searchTerm) || 
                    (s.description && s.description.toLowerCase().includes(searchTerm)));
        });
        this.filteredSchemas = filtered;
        this.renderSchemas(filtered);
        this.updateVisibleCount();
    }

    filterByType(type) {
        if (type === 'all') {
            this.renderSchemas(this.filteredSchemas);
            return;
        }
        const filtered = this.filteredSchemas.filter(s => {
            if (!s.file_type) return false;
            return s.file_type.toLowerCase() === type;
        });
        this.renderSchemas(filtered);
    }

    async deleteSchema(id) {
        if (!confirm('Supprimer ce compte rendu définitivement ?')) return;
        
        try {
            const response = await fetch(`/schemas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.schemas = this.schemas.filter(s => s.id !== id);
                this.renderTree();
                this.selectGt(this.currentGt);
                this.showToast('Compte rendu supprimé avec succès', 'success');
            } else {
                this.showToast(data.message || 'Erreur', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('Erreur de connexion', 'error');
        }
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showToast(message, type = 'info') {
        const colors = { success: '#28a745', error: '#dc3545', info: '#17a2b8' };
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed; bottom: 20px; right: 20px;
            background: ${colors[type] || colors.info};
            color: white; padding: 15px 25px; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999; max-width: 400px;
            animation: slideIn 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// ============================================ //
// FONCTIONS GLOBALES                           //
// ============================================ //

let schemaManager;

document.addEventListener('DOMContentLoaded', function() {
    schemaManager = new SchemaManager();

    window.openCreateSchemaModal = function(gt) {
        const modal = document.getElementById('createSchemaModal');
        const bsModal = new bootstrap.Modal(modal);
        
        document.getElementById('createSchemaForm').reset();
        document.getElementById('schemaModalTitle').textContent = gt ? 
            `Nouveau CR - ${gt}` : 'Nouveau compte rendu';
        
        if (gt) {
            document.getElementById('schemaCategory').value = gt;
        }
        
        setTimeout(toggleSubCategory, 200);
        bsModal.show();
    };

    window.toggleView = function() {
        if (schemaManager) schemaManager.toggleView();
    };
});

// CSS d'animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>

@endsection