@php
    use App\Filament\Resources\PermanenceResource\Pages\CreatePermanence;
    use App\Models\User;
    use App\Models\Service;
    use App\Models\permanenceUsers;
    use App\Models\Departement;
    use App\Functions\DateFunction;
    use carbon\carbon;

    $lastKValue = null;
    $record = $this->record;

    $departement = Departement::where('id', $record->departement_id)
        ->get()
        ->value('nom_departement'); // ok

    $firstPermanenceDay = Carbon::parse($record->date_debut)->TranslatedFormat('l, d M Y');

    $lastPermanenceDay = Carbon::parse($record->date_fin)->TranslatedFormat('l, d M Y');

    $datesPlage = Datefunction::getDateForSpecificDayBetweenDates($record->date_debut, $record->date_fin, config('app.jourCible'));

    $months = [];
    $days = $datesPlage;
    $users = [];

    //putting months in an array
    foreach ($days as $key => $day) {
        if (!in_array(carbon::parse($day)->TranslatedFormat('F'), $months)) {
            $months[] = carbon::parse($day)->TranslatedFormat('F');
        }
    }

    $users = [];
    $userNames = [];
    $intermediateArray = [];
    $emptyArray = [];

    $users = [];

    foreach ($record->order as $item) {
        foreach ($item['Employés'] as $employe) {
            $users[] = $employe['users'];
        }
    }

    $usersModels = [];
    foreach ($users as $key => $user) {
        $userModels[] = User::find($user);
    }

    $usersByService = collect($userModels)
        ->groupBy('service_id')
        ->toArray();

    $selectedUsers = [];



    $serviceOrder = collect($usersByService)
        ->keys()
        ->toArray(); // Obtenez l'ordre des services

$serviceIndexes = array_fill_keys($serviceOrder, 0); // Initialisez l'index de l'utilisateur pour chaque service

$services = [] ;
foreach ($userModels as $key => $s) {
  
    $c = Service::where('id',$s->service_id)->first();

    if(!in_array( $c, $services))
    {
          $services[] =  $c;
    }
  
}
$services = collect($services);

foreach ($days as $date) {
    $usersForDate = [];

    foreach ($serviceOrder as $serviceId) {
        // Obtenez l'index de l'utilisateur pour ce service
        $index = $serviceIndexes[$serviceId];

        // Sélectionnez l'utilisateur pour ce service à partir de son index
            $selectedUser = $usersByService[$serviceId][$index];

            // Mettez à jour l'index pour le prochain utilisateur du service
        $serviceIndexes[$serviceId] = ($index + 1) % count($usersByService[$serviceId]);

        // Ajoutez cet utilisateur à la liste pour cette date
        $usersForDate[] = $selectedUser;
    }

    // Ajoutez les utilisateurs sélectionnés pour cette date au tableau final
    $selectedUsers[$date] = $usersForDate;
}

$identifiers = [];

foreach ($selectedUsers as $usersForDate) {
    $userIds = array_column($usersForDate, 'id');
        $identifiers = array_merge($identifiers, $userIds);
    }

    foreach ($identifiers as $key => $user) {
        if ($user != null) {
            $userNames[] = User::find($user)->name;
        }
    }

    $y = 0;
    $z = 0;

@endphp
<x-filament-widgets::widget>
    <x-filament::section>
        <!-- component -->
        <h2 class="text-4xl  text-center py-6 font-extrabold dark:text-white">Planning des permanences pour les dates du
            {{ $firstPermanenceDay }} au {{ $lastPermanenceDay }}</h2>
        <h2 class="text-4xl  text-center py-2 font-extrabold dark:text-white">SPT / {{ $departement }}</h2>
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Dates</th>
                        @foreach ($services as $service)
                            <th scope="col" class="px-6 py-4 font-medium text-gray-900">{{ $service->nom_service }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @if ($days)
                        @foreach ($months as $month)
                            <tr class="hover:bg-teal-50">
                                <th class=" px-6 py-4 font-normal text-gray-900 text-center">
                                    {{ $month }}
                                </th>
                            </tr>
                            @foreach ($days as $day)
                                @if (carbon::parse($day)->translatedFormat('F') == $month)
                                    <tr class="hover:bg-gray-50">
                                        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                                            <div class="relative h-10 w-10">
                                            </div>
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-700">
                                                    {{ carbon::parse($day)->translatedFormat('l, d F Y') }}</div>
                                            </div>
                                        </th>
                                        @for ($k = 0; $k < $services->count(); $k++)
                                            @if ($y + $k < count($userNames))
                                                <td class="px-6 py-4">
                                                    <span class="h-1.5 w-1.5 rounded-full">
                                                        {{ $userNames[$y + $k] }}
                                                    </span>
                                                </td>
                                            @endif
                                        @endfor

                                        @php
                                            $y = $y + $k;
                                        @endphp

                                        @if ($loop->last)
                                        @break
                                    @endif

                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif

            </tbody>
        </table>
    </div>

</x-filament::section>
</x-filament-widgets::widget>
