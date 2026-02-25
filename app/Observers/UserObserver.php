<?php
    namespace App\Observers;

    use App\Models\User;
    use App\Models\ActivityLog;
    use Illuminate\Support\Facades\Auth;

    class UserObserver
    {
        public function created(User $user)
        {
            ActivityLog::log('Création d\'un utilisateur', 'Utilisateur créé: ' . $user->name.' '.$user->prenom.' '.$user->email);
        }

        public function updated(User $user)
        {
            ActivityLog::log('Modification d\'un utilisateur', 'Utilisateur modifié: ' . $user->name.' '.$user->prenom.' '.$user->email);
        }

        public function deleted(User $user)
        {
            ActivityLog::log('Suppression d\'un utilisateur', 'Utilisateur supprimé: ' . $user->name.' '.$user->prenom.' '.$user->email);
        }
    }
?>