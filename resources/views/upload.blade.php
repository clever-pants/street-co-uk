<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-US-Compatible" content="ie-edge">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>

    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18]">
        <h1 class="my-6 text-3xl text-center">Upload page</h1>
        <form class="my-6 text-center" method="post" action="/" enctype="multipart/form-data">
            @csrf
            <label for="upload">Upload CSV </label>
            <input class="ml-6" type="file" id="upload" name="upload"><br><br>
            <input class="bg-gray-100 border border-gray-400 rounded-sm px-2 py-1" type="submit" value="Submit">
        </form>
        @if (isset($names_out))
        <table class="mt-12 mx-auto p-4 border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="p-4 border border-gray-300">Title</th>
                    <th class="p-4 border border-gray-300">First name</th>
                    <th class="p-4 border border-gray-300">Initial</th>
                    <th class="p-4 border border-gray-300">Last name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($names_out as $client)
                <tr>
                    <td class="p-4 border border-gray-300">{{ $client['title'] }}</td>
                    <td class="p-4 border border-gray-300">{{ $client['first_name'] }}</td>
                    <td class="p-4 border border-gray-300">{{ $client['initial'] }}</td>
                    <td class="p-4 border border-gray-300">{{ $client['last_name'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @elseif (isset($error_message))
        <p class="text-center"><strong>{{ $error_message }}</strong></p>
        @endif
    </body>
</html>
