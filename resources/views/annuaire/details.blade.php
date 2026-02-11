<!-- Modal amélioré avec animations -->
<div class="modal fade animate__animated" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animate__animated animate__zoomIn">
        <div class="modal-content border-0 shadow-2xl overflow-hidden">
            <!-- Header avec animation -->
            <div class="modal-header bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4">
                <div class="flex items-center gap-3 animate__animated animate__fadeInLeft">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-lg font-bold" id="detailsModalLabel">
                            <span id="modal-organisme">Détails de la structure</span>
                        </h5>
                        <p class="text-sm text-white/80 font-medium">Informations complètes</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body avec espace réduit -->
            <div class="modal-body bg-gray-50 p-4 max-h-[70vh] overflow-y-auto">
                
                <!-- Informations principales (2 colonnes compactes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <!-- Colonne gauche -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-info-circle text-xs"></i> 
                            <span>Informations principales</span>
                        </h6>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Organisme:</span>
                                <span class="text-gray-800 font-semibold" id="modal-organisme-text">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Catégories:</span>
                                <span class="text-gray-800" id="modal-categories">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Type:</span>
                                <span class="text-gray-800" id="modal-type_structure">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Public:</span>
                                <span class="text-gray-800" id="modal-public_cible">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Zone:</span>
                                <span class="text-gray-800" id="modal-zone">-</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="font-medium text-gray-600">Site web:</span>
                                <span id="modal-site" class="text-[#255156]">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite - Localisation -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-xs"></i> 
                            <span>Localisation</span>
                        </h6>
                        
                        <!-- Siège social -->
                        <div class="mb-3 p-2 bg-blue-50/50 rounded border border-blue-100">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-landmark text-blue-500 text-xs"></i>
                                <span class="font-semibold text-blue-700 text-xs">SIÈGE SOCIAL</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-16 text-gray-500">Ville:</span>
                                    <span class="text-gray-700 font-medium" id="modal-siege_ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-16 text-gray-500">Adresse:</span>
                                    <span class="text-gray-700 truncate" id="modal-siege_adresse" title="-">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Antenne locale -->
                        <div class="p-2 bg-green-50/50 rounded border border-green-100">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-map-pin text-green-500 text-xs"></i>
                                <span class="font-semibold text-green-700 text-xs">ANTENNE LOCALE</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Ville:</span>
                                    <span class="text-gray-700 font-medium" id="modal-ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Code postal:</span>
                                    <span class="text-gray-700" id="modal-code_postal">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Adresse:</span>
                                    <span class="text-gray-700 truncate" id="modal-adresse" title="-">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact (ligne unique compacte) -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                        <i class="fas fa-address-book text-xs"></i> 
                        <span>Contact</span>
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-phone text-green-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Téléphone</div>
                                <div class="font-medium" id="modal-telephone">-</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-envelope text-blue-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Email</div>
                                <span class="font-medium text-[#255156] truncate" id="modal-email">-</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-user text-purple-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Contact</div>
                                <div class="font-medium truncate" id="modal-contact">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-2">
                        <h6 class="text-[#255156] font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-file-alt text-xs"></i> 
                            <span>Description</span>
                        </h6>
                        <span class="text-xs bg-[#255156]/10 text-[#255156] px-2 py-1 rounded-full font-medium">
                            <i class="fas fa-align-left mr-1"></i> Détails
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed p-2 bg-gray-50 rounded" id="modal-description">
                        -
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-bed text-xs"></i> 
                            <span>Hébergement</span>
                        </h6>
                        <div class="text-sm text-gray-700" id="modal-hebergement">-</div>
                    </div>
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-list-ul text-xs"></i> 
                            <span>Détails spécifiques</span>
                        </h6>
                        <div class="text-sm text-gray-700" id="modal-details">-</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white p-3 border-t border-gray-200">
                <div class="flex justify-between items-center w-full">
                    <div class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span id="modal-maj">Dernière mise à jour</span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" 
                                class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-all"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>