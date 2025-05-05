@props(['table_name', 'table_data'])
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body>
<div class="min-h-full">

    <x-nav_bar/>


    <div class="flex">
        @php
            $tables = DB::select('SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = "POMSE"');
            $tables = array_slice($tables, 2);
            array_splice($tables, 5, 2);
            array_splice($tables, 7, 1);
        @endphp

        <x-side_bar :tables="$tables"/>


        <main class="flex-1 p-4">
            @if(isset($table_name))
                <x-main_content :table_name="$table_name" :table_data="$table_data" :tables="$tables"/>
            @else
                <x-cards :tables="$tables"/>
            @endif
        </main>

    </div>
</div>
</body>
</html>
