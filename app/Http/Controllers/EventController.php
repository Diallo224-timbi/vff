<?php
// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInscription;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements
     */
    public function index(Request $request)
    {
        $events = Event::with('createur')
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('periode'), function ($q) use ($request) {
                if ($request->periode === 'a_venir') {
                    $q->where('date_debut', '>', now());
                } elseif ($request->periode === 'passes') {
                    $q->where('date_fin', '<', now());
                }
            })
            ->orderBy('date_debut','desc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable',
            'type' => 'required|in:réunion,formation,atelier,autre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|max:255',
            'organisateur' => 'nullable|max:255',
            'nombre_places' => 'nullable|integer|min:1'
        ]);

        $event = Event::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'date_debut' => Carbon::parse($request->date_debut)->format('Y-m-d H:i:s'),
            'date_fin' => Carbon::parse($request->date_fin)->format('Y-m-d H:i:s'),
            'lieu' => $request->lieu,
            'organisateur' => $request->organisateur,
            'nombre_places' => $request->nombre_places,
            'cree_par' => auth()->id()
        ]);

        ActivityLog::log('Création événement', 'Événement créé: ' . $event->titre);

        return redirect()->route('events.show', $event)
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Afficher un événement
     */
    public function show(Event $event)
    {
        $event->load(['createur', 'participants']);

        $userInscription = auth()->check()
            ? $event->inscriptions()->where('user_id', auth()->id())->first()
            : null;

        return view('events.show', compact('event', 'userInscription'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable',
            'type' => 'required|in:réunion,formation,atelier,autre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|max:255',
            'organisateur' => 'nullable|max:255',
            'nombre_places' => 'nullable|integer|min:1'
        ]);

        $event->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'date_debut' => Carbon::parse($request->date_debut)->format('Y-m-d H:i:s'),
            'date_fin' => Carbon::parse($request->date_fin)->format('Y-m-d H:i:s'),
            'lieu' => $request->lieu,
            'organisateur' => $request->organisateur,
            'nombre_places' => $request->nombre_places
        ]);

        ActivityLog::log('Modification événement', 'Événement modifié: ' . $event->titre. ' par ' . auth()->user()->name);

        return redirect()->route('events.index', $event)
            ->with('success', 'Événement mis à jour avec succès.');
    }

    public function downloadIcal(Event $event)
    {
        $icsContent = "BEGIN:VCALENDAR
        VERSION:2.0
        PRODID:-//Votre Application//FR
        CALSCALE:GREGORIAN
        METHOD:PUBLISH
        BEGIN:VEVENT
        UID:{$event->id}@" . request()->getHost() . "
        DTSTAMP:" . now()->format('Ymd\THis') . "
        DTSTART:" . $event->date_debut->format('Ymd\THis') . "
        DTEND:" . $event->date_fin->format('Ymd\THis') . "
        SUMMARY:" . addslashes($event->titre) . "
        DESCRIPTION:" . addslashes($event->description ?? '') . "
        LOCATION:" . addslashes($event->lieu ?? '') . "
        END:VEVENT
        END:VCALENDAR";

        //log de l'activité
        ActivityLog::log('Téléchargement iCal', 'iCal téléchargé pour: ' . $event->titre. ' par ' . auth()->user()->name);
            return response($icsContent)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $event->titre . '.ics"');
    }
    /**
     * Supprimer un événement
     */
    public function destroy(Event $event)
    {
        $titre = $event->titre;
        $event->delete();

        ActivityLog::log('Suppression événement', 'Événement supprimé: ' . $titre. ' par ' . auth()->user()->name);

        return redirect()->route('events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * S'inscrire à un événement
     */
    public function inscrire(Event $event)
    {
        if ($event->inscriptions()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        if ($event->nombre_places && $event->inscriptions()->count() >= $event->nombre_places) {
            return back()->with('error', 'Désolé, cet événement est complet.');
        }

        EventInscription::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'statut' => 'inscrit'
        ]);

        ActivityLog::log('Inscription événement', 'Inscription à: ' . $event->titre);

        return back()->with('success', 'Vous êtes inscrit à cet événement.');
    }

    /**
     * Se désinscrire d'un événement
     */
    public function desinscrire(Event $event)
    {
        $inscription = $event->inscriptions()->where('user_id', auth()->id())->first();

        if ($inscription) {
            $inscription->delete();
            ActivityLog::log('Désinscription événement', 'Désinscrit de: ' . $event->titre);
            return back()->with('success', 'Vous êtes désinscrit de cet événement.');
        }

        return back()->with('error', 'Vous n\'êtes pas inscrit à cet événement.');
    }

    /**
     * Vue calendrier
     */
    public function calendrier(Request $request)
    {
        $events = Event::query()
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('periode'), function ($q) use ($request) {
                if ($request->periode === 'a_venir') {
                    $q->where('date_debut', '>', now());
                } elseif ($request->periode === 'passes') {
                    $q->where('date_fin', '<', now());
                }
            })
            ->orderBy('date_debut')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->titre,
                    'start' => $event->date_debut->format('Y-m-d H:i:s'),
                    'end' => $event->date_fin->format('Y-m-d H:i:s'),
                    'type' => $event->type,
                    'location' => $event->lieu,
                    'url' => route('events.show', $event)
                ];
            });

        return view('events.calendrier', compact('events'));
    }
}