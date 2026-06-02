@props([
    'title' => 'Validation Required',
    'description' => 'Please correct the highlighted fields.',
    'errors' => null,
])

@php
    $errorList = [];

    if ($errors && method_exists($errors, 'all')) {
        $errorList = $errors->all();
    }
@endphp

<div class="rounded-2xl border border-danger/20 bg-danger/10 px-4 py-3 text-danger" role="alert" aria-live="assertive">
    <div class="font-semibold">{{ $title }}</div>
    <div class="mt-1 text-sm">{{ $description }}</div>

    @if(! empty($errorList))
        <ul class="mt-3 list-disc space-y-1 pl-5 text-sm">
            @foreach($errorList as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>