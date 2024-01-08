<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Presence;
use Filament\Notifications\Notification;

class RePointage extends Component
{
    // public function render()
    // {
    //     return view('livewire.re-pointage');
    // }

    public function departure()
    {
        $latestPresenceForThisUser = Presence::where('user_id', auth()->user()->id)->where('created_at', today())->first();

        if (Carbon::now() < config('app.heure_depart')) {
            Notification::make()
                ->title('Attention')
                ->body('Il n\'est pas encore ' . config('app.heure_depart')->format('H:i') . '. Vous ne pouvez pas partir!!!')
                ->icon('heroicon-o-shield-exclamation')
                ->iconColor('danger')
                ->duration(5000)
                ->send();
        } else {
            $latestPresenceForThisUser->update([
                "heure_depart" => now()
            ]);

            Notification::make()
                ->title('Bonne soirée')
                ->body('Votre départ a été enrégistré!')
                ->icon('heroicon-o-hand-raised')
                ->iconColor(Color::Cyan)
                ->duration(5000)
                ->send();
        }

    }
}
