<?php

namespace App\Http\Controllers;

use carbon\carbon;
use App\Models\User;
use App\Models\Service;
use App\Models\Permanence;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\permanenceUsers;
use App\Functions\DateFunction;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadController extends Controller
{
    public function downloadPdf(Permanence $record)
    {


        $loggedUser = auth()->user();

        $loggedService = Service::where('id', $loggedUser->service_id)->first();

        $loggedDepartement = Departement::where('id', $loggedService->departement_id)->first();

        $record = Permanence::where('departement_id', $loggedDepartement->id)
            ->orderBy('created_at', 'desc')
            ->first();


        if ($record) {

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

            $selectedUsers = [];

            $serviceOrder = collect($usersByService)
                ->keys()
                ->toArray(); // Obtenez l'ordre des services

            $serviceIndexes = array_fill_keys($serviceOrder, 0); // Initialisez l'index de l'utilisateur pour chaque service


            $services = [];
            foreach ($userModels as $key => $s) {

                $c = Service::where('id', $s->service_id)->first();

                if (!in_array($c, $services)) {
                    $services[] = $c;
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

        }
        // ==============
        // ============================================================
        //DEPARTEMENTS
        $data = new Party([
            'nom_departements' => $departement,
            'services' => $services,
            // 'dates' =>  $days,
            'months' => $months,
            'days' => $days,
            // 'annee' => $annee,
            'userNames' => $userNames,
            'currentRecord' => $record

            // 'custom_fields' => [
            //     'note'        => 'IDDQD',
            //     'business id' => '365#GG',
            // ],
        ]);

        // $services= new Party($lesServices);


        //SERVICES
        $customer = new Party([

            'name' => 'Ashley Medina',
            'address' => 'The Green Street 12',
            'code' => '#22663214',
            'custom_fields' => [
                    'order number' => '> 654321 <',
                ],
        ]);


        $servicesItems = [];

        foreach ($services as $service) {
            $servicesItems[] = (new InvoiceItem())->title($service->nom_service);
        }

        $items = [
            (new InvoiceItem())
                ->title('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
            // (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
            // (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            // (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            // (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
            // (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            // (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            // (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            // (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
            // (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
            // (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
            // (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
            // (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            // (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
            // (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
            // (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            // (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
            // (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [

            $departement,
        ];
        $notes = implode("<br>", $notes);



        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            // ->status(__('invoices::invoice.paid'))
            // ->sequence(667)
            // ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($data)
            ->buyer($customer)
            // ->seller($a)
            ->date(now())
            ->dateFormat('d/m/Y')
            // ->payUntilDays(14)
            // ->currencySymbol('$')
            // ->currencyCode('USD')
            // ->currencyFormat('{SYMBOL}{VALUE}')
            // ->currencyThousandsSeparator('.')
            // ->currencyDecimalPoint(',')
            ->filename($data->nom_departements)
            // ->setCustomData($servicesItems)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('logo.png'))
            // ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }
}
