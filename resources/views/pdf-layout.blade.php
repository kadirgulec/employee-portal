<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ausdrucken {{ $illnessNotification->user->first_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col items-center ">

    <main class="">
        <h3 class="mt-20 font-semibold text-center">Personalstammblatt bei Arbeitsunfähigkeit</h3>
        <section class="grid mt-10 gap-y-2" style="grid-template-columns: 11rem 1rem 4rem 11rem;">
            <p>Personal-Nr.</p>
            <p class="text-center">:</p>
            <p class="col-span-2 bg-gray-200 border-b border-black">{{ $illnessNotification->user->personal_number }}</p>
            <p>Name</p>
            <p class="text-center">:</p>
            <p class="col-span-2 bg-gray-200 border-b border-black">{{ $illnessNotification->user->last_name }}</p>
            <p>Vorname</p>
            <p class="text-center">:</p>
            <p class="col-span-2 bg-gray-200 border-b border-black">{{ $illnessNotification->user->first_name }}</p>
            <p>Krankmeldung ab</p>
            <p class="text-center">:</p>
            <p class="col-span-2 bg-gray-200 border-b border-black">{{ date('d-m-Y',strtotime($illnessNotification->illness_notification_at)) }}
            </p>
            <p>Arztbesuch am</p>
            <p class="text-center">:</p>
            <p class="col-span-2 bg-gray-200 border-b border-black">
                {{$illnessNotification->doctor_visited_at ? date('d-m-Y' , strtotime($illnessNotification->doctor_visited_at)) : ""}}
            <p>§ 5 EntgFG</p>
            <p class="text-center">:</p>
            <div></div>
            <p class="space-x-5"><input class="w-5 h-5 ml-5 accent-black" type="radio"
                @if ($illnessNotification->entgFG == 1)
                    checked
                @else

                @endif
                >  <span
                class="bg-gray-200">ja / nein</span>
                <input class="w-5 h-5 accent-black" type="radio"
                @if ($illnessNotification->entgFG == 0)
                    checked
                @else

                @endif
                >
            </p>
            <p class="mt-5">AU-Grund</p>
            <p class="mt-5 text-center">:</p>
            <div></div>
            <div class="flex mt-5">
                <div class="flex flex-col ml-5 whitespace-nowrap">
                    <label for=""><input class="w-5 h-5 accent-black" type="checkbox"
                        @if ($illnessNotification->incapacity_reason == 'AU wegen Krankheit')
                            checked
                        @else
                        @endif
                        > <span
                            class="bg-gray-200">a)</span> AU wegen Krankheit</label>
                    <label for=""><input class="w-5 h-5 accent-black" type="checkbox"
                        @if ($illnessNotification->incapacity_reason == 'AU wegen Arbeitsunfall')
                            checked
                        @else
                        @endif
                        > <span class="bg-gray-200">b)</span> AU
                        wegen Arbeitsunfall</label>
                    <label for=""><input class="w-5 h-5 accent-black" type="checkbox"
                        @if ($illnessNotification->incapacity_reason == 'AU bei stationärer Krankenhausbehandlung')
                            checked
                        @else

                        @endif
                        > <span class="bg-gray-200">c)</span> AU
                        bei stationärer Krankenhausbehandlung</label>
                </div>
            </div>

        </section>


        <section class="grid grid-cols-2 mt-32">
            <div class="row-span-3 self-center -mt-5">

                @if ($illnessNotification->sent_at)
                    <p class="self-end -mb-4">{{ date('d.m.Y', strtotime($illnessNotification->sent_at)) }}</p>
                @else
                    <p></p>
                @endif

                <p class="tracking-[3px] text-xs">.............................</p>

                <p>Datum</p>
            </div>

            @if($illnessNotification->sent_at)
                <div class="row-span-3">
                     <img src="{{ public_path('storage/images/stampel.png') }}" alt="stampel">
                </div>
            @else

                <div class="row-span-3 self-center -mt-5">
                    <p></p>

                    <p class="tracking-[3px] text-xs">..........................................................</p>

                    <p class="text-center z-10">Unterschrift Arbeitgeber</p>
                </div>
            @endif



        </section>
    <p class="mt-32">Bemerkungen:</p>
    <p class="ml-4"> {{$illnessNotification->doctor_certificate}}</p>
    <p class="ml-4"> {{$illnessNotification->note}}</p>

    </main>
</body>

</html>
