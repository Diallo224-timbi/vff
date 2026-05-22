 @if($event->nombre_places)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        
                        <div class="flex-1">
                            <div class="text-sm text-gray-500">Places disponibles</div>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#255156] to-[#3a7077] rounded-full" 
                                         style="width: {{ min(100, ($event->nombre_inscrits / $event->nombre_places) * 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium {{ $event->places_restantes > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $event->nombre_inscrits }}/{{ $event->nombre_places }}
                                </span>
                            </div>
                            <!-- @if($event->places_restantes > 0)
                                <div class="text-sm text-green-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $event->places_restantes }} place(s) restante(s)
                                </div>
                            @elseif($event->places_restantes === 0)
                                <div class="text-sm text-red-600 mt-1">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Complet
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>