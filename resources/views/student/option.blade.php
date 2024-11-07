<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Options</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <div class="mt-4 bg-white p-4 rounded shadow">
        <h1 class="text-xl font-bold">Question Title Here</h1>
        <h2 class="text-lg">Options:</h2>

        <div id="options-container" class="mt-4">
            @if ($options && $options->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($options as $option)
                        <div class="border p-4 rounded shadow bg-white hover:bg-blue-100 transition duration-300">
                            <p class="text-lg">{{ $option->answer_text }}</p>
                            <p class="mt-2"><strong>Correct Answer:</strong> {{ $option->is_correct ? 'Yes' : 'No' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-red-500">No options available for this question.</p>
            @endif
        </div>
    </div>
</div>

</body>
</html>
