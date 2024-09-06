<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ausdrucken Bill</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<main class="max-w-3xl mx-auto p-6 bg-white my-6" id="bill">

    <div class="flex justify-between items-center">
        <div>

        </div>
        <div class="auto-cols-auto">
            <h1 class="text-3xl font-bold">Arbeitsauftrag</h1>
            <h2 class="text-2xl ">aks Ticket-Nummer: #12332</h2>
        </div>
        <div class="place-self-end flex flex-col">
            <div class="self-end">
                <!--  Company logo  -->
                <img
                    src="https://www.aks-service.de/wp-content/uploads/2022/10/aks_logo_mit_system_und_medienhaus_20200114-300x151.png"
                    alt="company-logo"
                    height="150" width="150">
            </div>

            <div class="text-right">
                <p>
                    aks Service GmbH
                </p>
                <p class="text-gray-500 text-sm">
                    Sperberweg 1a
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    52385 Nideggen
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    Tel: +49 2427 906940
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    Fax: +49 2427 9069423
                </p>

            </div>
        </div>
    </div>

    <div class="flex justify-between">
        <div>
            <p class="font-bold">Zustimmung zur Bearbeitung de folgenden Auftrags:</p>
            <p><span class="font-bold">Ticket Überschrift: </span><span>Test</span> / Datum: 11/11/11</p>
        </div>
        <div class="self-end text-right">
            <p class="text-gray-500 text-sm mt-1">
                E-Mail: info@aks-service.de
            </p>
            <p class="text-gray-500 text-sm mt-1">
                www.aks-service.de
            </p>
        </div>
    </div>

    <!-- Client info -->
    <div class="flex space-x-16 border-black border-2">
        <div class="p-3">
            <p>Name, Vorname:</p>
            <p>Firma: Linzenich Tino</p>
            <p>Straße, Ort: Sperberweg 1, 52385 Nideggen</p>
            <p>Telefon:</p>
        </div>
        <div class="p-3">
            <p>E-Mail: </p>
            <p>Handy: </p>
            <br>
            <p>KD-Nr.: 15533</p>
        </div>
    </div>

    <!--    Kostenübersicht-->
    <div class="border-2 border-black -mt-0.5 p-1">
        <h3 class="font-bold text-xl">Kostenübersicht:</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa dicta dolorem expedita fugiat impedit ipsa
            iure placeat quaerat qui quod ratione similique sint suscipit temporibus veniam, vitae voluptatem
            voluptates. Doloremque.</p>
    </div>

    <!--    zubehör-->
    <div class="border-2 border-black -mt-0.5 p-1">
        <h4>Zubehör:</h4>
        <p>PC</p>
        <p>Laptop</p>
    </div>

    <!--    Reparaturkostenfreigabe-->
    <div class="mt-4 pl-2">
        <p class="text-xl">Reparaturkostenfreigabe: <span class="font-bold"> 49,00 € zuzüglich MwSt.</span></p>
        <p class="mt-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis cumque, ex exercitationem
            impedit iure nam reiciendis. Ad aperiam et explicabo fuga neque, nesciunt odit, officia reiciendis rem vero
            voluptatem voluptates. Assumenda commodi eveniet laborum officia perferendis repudiandae temporibus! Et eum
            incidunt optio pariatur quasi vel?</p>
    </div>

    <!--    second box with border-->
    <div class="border-2 border-black mt-2">
        <div class="pl-2">
            <p class="font-bold">Sprechen Sie uns gerne auch auf folgendes weiteres Angebot an:</p>
            <div class="grid grid-cols-2 pl-4">
                <p>- Lorem ipsum dolor.</p>
                <p>- Lorem ipsum dolor.</p>
                <p>- Lorem ipsum dolor.</p>
                <p>- Lorem ipsum dolor.</p>
            </div>
            <p class="font-bold text-right mr-6">...gerne beraten wir Sie hierzu!</p>
        </div>
        <div class="border-t-2 border-black pl-2">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores enim esse illo non omnis quas qui
                quo repudiandae suscipit vel.</p>
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
    <h2 class="font-bold text-xl">Allgemeine wichtige Hinweise:</h2>
    <p>Lorem ipsum dolor sit amet.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim iste labore nostrum!</p>
    <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque.</p>
    <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A autem, excepturi facilis, fugit incidunt molestiae odit perspiciatis quam quis sapiente tempore temporibus?</p>

</main>

</body>

</html>
