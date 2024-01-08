<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Presence;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;

class Pointage extends Component
{
    
    public function arrival()
    {
        dd(date('l d  Y m --- w', strtotime(Carbon::today())));

        $latestPresenceForThisUser = Presence::where('user_id', auth()->user()->id)->where('created_at', today())->first();

        if ($latestPresenceForThisUser) {

          
            if ($this->dailyCheck($latestPresenceForThisUser->created_at)) {

                Notification::make()
                    ->title('Attention')
                    ->body('Votre présence est déjà enrégistrée pour aujourd\'hui!')
                    ->icon('heroicon-o-shield-exclamation')
                    ->iconColor(Color::Amber)
                    ->duration(5000)
                    ->send();
            }
        } else {
            Presence::firstOrCreate([
                'date' => today(),
                'user_id' => auth()->user()->id,
                'heure_arrivee' => now(),
                'created_at' => today(),
                'updated_at' => now(),
            ]);

            Notification::make()
                ->title('Bienvenue')
                ->body('Votre arrivée a été enrégistrée!')
                ->icon('heroicon-o-user-plus')
                ->iconColor('success')
                ->duration(5000)
                ->send();
        }


    }

   

    private function dailyCheck($arrivalTime): bool
    {

        $arrival = Carbon::parse($arrivalTime)->addDay();

        return $arrival > Carbon::now();
    }
}
