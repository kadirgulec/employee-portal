<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechnung</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<main class="mx-auto bg-white w-[210mm] h-[297mm] text-sm" id="bill">

    <div class="flex justify-between items-center">
        <div>

        </div>
        <div class="auto-cols-auto">
            <h1 class="text-3xl font-bold">Arbeitsauftrag</h1>
            <h2 class="text-2xl ">aks Ticket-Nummer: #</h2>
        </div>
        <div class="place-self-end flex flex-col">
            <div class="self-end">
                <!--  Company logo  -->
                <img
                    src="https://www.aks-service.de/wp-content/uploads/2022/10/aks_logo_mit_system_und_medienhaus_20200114-300x151.png"
                    alt="company-logo"
                    height="100" width="100">
            </div>

            <div class="text-right">
                <p>
                    aks Service GmbH
                </p>
                <p class="text-gray-700 text-xs mt-1">
                    Sperberweg 1a
                </p>
                <p class="text-gray-700 text-xs ">
                    52385 Nideggen
                </p>
                <p class="text-gray-700 text-xs mt-1">
                    Tel: +49 2427 906940
                </p>
                <p class="text-gray-700 text-xs">
                    Fax: +49 2427 9069423
                </p>

            </div>
        </div>
    </div>

    <div class="flex justify-between">
        <div>
            <p class="font-bold text-sm">Zustimmung zur Bearbeitung de folgenden Auftrags:</p>
            <p><span class="font-bold text-sm">Ticket Überschrift: </span><span>Test</span> / Datum: {{$bill->date}}</p>
        </div>
        <div class="self-start text-right">
            <p class="text-gray-700 text-xs">
                E-Mail: info@aks-service.de
            </p>
            <p class="text-gray-700 text-xs">
                www.aks-service.de
            </p>
        </div>
    </div>

    <!-- Client info -->
    <div class="grid grid-cols-2 border-black border-2">
        <div class="p-2">
            <p>Name, Vorname: {{$bill->customer->last_name}}, {{$bill->customer->first_name}}</p>

            <p>Straße, Ort: {{$bill->customer->address}}, {{$bill->customer->city}}</p>
            <p>Telefon: {{$bill->customer->phone}}</p>
        </div>
        <div class="p-2">
            <p>E-Mail: {{$bill->customer->email}}</p>
            <p>Handy: {{$bill->customer->mobile}}</p>
            <p>KD-Nr.: {{$bill->customer->id}}</p>
        </div>
    </div>

    <!--    Kostenübersicht-->
    <div class="border-2 border-black -mt-0.5 p-1">
        <h3 class="font-bold text-lg">Kostenübersicht:</h3>
        <p class="-mt-0.5">Die Kosten für eine Stunde Support liegen bei 105,00 € zzgl. MwSt. innerhalb der
            Öffnungszeiten.</p>
        <p class="-mt-0.5">Die Supportzeiten werden, wenn nicht anders vereinbart, nach tatsächlichem Aufwand
            berechnet.</p>
        <p class="-mt-0.5">Die Supportzeiten werden, wenn nicht anders vereinbart, nach tatsächlichem Aufwand
            berechnet.</p>
        <p class="-mt-0.5">Die Supportzeiten werden, wenn nicht anders vereinbart, nach tatsächlichem Aufwand
            berechnet.</p>
        <p class="-mt-0.5">Die Supportzeiten werden, wenn nicht anders vereinbart, nach tatsächlichem Aufwand
            berechnet.</p>
    </div>

    <!--    Produkt-->
    <div class="border-2 border-black -mt-0.5 p-1">
        @foreach($bill->positions as $position)
            <h3 class="font-semibold text-base mt-1">{{$loop->iteration}} - {{$position->product_name}} <span
                    class="text-sm font-normal">({{$position->product_price}}€ x {{$position->quantity}} = {{$position->product_price * $position->quantity}}€)</span>
            </h3>
            <div
                class="[&>ul>li]:list-disc [&>*]:list-inside [&>h3]:font-semibold [&>*]:px-2 [&>ol>li]:list-decimal [&>ol]:ml-2 [&>ul]:ml-2">
                {!! $position->product_description !!}
            </div>

        @endforeach
    </div>

    <!--    Reparaturkostenfreigabe-->
    <div class="mt-2 pl-2">
        <p class="text-lg">Reparaturkostenfreigabe: <span class="font-bold"> {{number_format($bill->total_price,2,',')}} € zuzüglich MwSt.</span>
        </p>
        <p class="mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis cumque, ex exercitationem
            impedit iure nam reiciendis. Ad aperiam et explicabo fuga neque, nesciunt odit, officia reiciendis rem vero
            voluptatem voluptates. Assumenda commodi eveniet laborum officia perferendis repudiandae temporibus! Et eum
            incidunt optio pariatur quasi vel?</p>
    </div>

    <!--    second box with border-->
            {{--        if the bill has more than 1 positions this will be seen at second page of the pdf--}}
    @if((count($bill->positions)) > 1)
        <div class="border-2 border-black mt-2 break-before-page">
            @else
                <div class="border-2 border-black mt-2">
                    @endif

                    <div class="pl-2">
                        <p class="font-bold">Sprechen Sie uns gerne auch auf folgendes weiteres Angebot an:</p>
                        <div class="grid grid-cols-2 pl-4">
                            <p>- Hardware Check</p>
                            <p>- Neuinstallationen</p>
                            <p>- PC zu PC-Datenübertragung</p>
                            <p>- Viren- und Trojaner Check</p>
                        </div>
                        <p class="font-bold text-right mr-6">...gerne beraten wir Sie hierzu!</p>
                    </div>
                    <div class="border-t-2 border-black pl-2">
                        <p class="font-semibold">Hiermit verpflichte ich mich, Kosten, die durch Leistungen der aks
                            Service GmbH entstehen, nach den genannten Bedingungen zu akzeptieren.</p>
                        <div class="flex justify-between space-x-2 mt-16">
                            <div class="w-1/2 border-t-2 border-black">
                                <p>Unterschrift</p>
                            </div>
                            <div class="w-1/2 border-t-2 border-black">
                                <p>Name in lesbarer Schrift</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!--    Allgemeine wichtige hinweis-->
                <h2 class="font-bold text-lg">Allgemeine wichtige Hinweise:</h2>
                <p>Lorem ipsum dolor sit amet.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim iste labore nostrum!</p>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque.</p>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A autem, excepturi facilis,
                    fugit incidunt molestiae odit perspiciatis quam quis sapiente tempore temporibus?</p>

</main>

</body>

</html>
