@extends('admin.layouts.app')

@section('content')
    @php
        $view = $view ?? (request('view') ?: 'due');
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-col">
            <h1 class="text-xl font-medium">Front Desk · Departures</h1>
            <div class="text-sm text-secondary-foreground">
                @if($view === 'checked_out')
                    Checked-out today ({{ $today->format('M d, Y') }})
                @else
                    Due today ({{ $today->format('M d, Y') }})
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form id="departures-search-form" method="GET" action="{{ route('admin.front-desk.departures') }}" class="flex flex-wrap items-center gap-2">
                <select id="departures-view" name="view" class="kt-input">
                    <option value="due" {{ $view === 'due' ? 'selected' : '' }}>Due today</option>
                    <option value="checked_out" {{ $view === 'checked_out' ? 'selected' : '' }}>Checked-out today</option>
                </select>

                <input id="departures-search" type="text" name="q" class="kt-input" placeholder="Search guest / phone / room" value="{{ request('q') }}" autocomplete="off" />

                @if($view === 'due')
                    <label class="flex items-center gap-2 text-sm text-secondary-foreground">
                        <input id="departures-include-overdue" type="checkbox" name="include_overdue" value="1" {{ request('include_overdue') ? 'checked' : '' }} />
                        Include overdue
                    </label>
                @endif
            </form>
            @if(request('q') || request('include_overdue') || request('view'))
                <a class="kt-btn kt-btn-outline" href="{{ route('admin.front-desk.departures') }}">Clear</a>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('departures-search-form');
                const input = document.getElementById('departures-search');
                const viewSelect = document.getElementById('departures-view');
                const includeOverdue = document.getElementById('departures-include-overdue');

                if (!form) return;

                let debounceId;
                if (input) {
                    input.addEventListener('input', function () {
                        window.clearTimeout(debounceId);
                        debounceId = window.setTimeout(function () {
                            form.submit();
                        }, 350);
                    });
                }

                if (includeOverdue) {
                    includeOverdue.addEventListener('change', function () {
                        form.submit();
                    });
                }

                if (viewSelect) {
                    viewSelect.addEventListener('change', function () {
                        form.submit();
                    });
                }
            });
        </script>
    @endpush

    <div class="grid gap-5">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <h3 class="kt-card-title">Departures</h3>
                <div class="text-sm text-secondary-foreground">
                    Total: {{ $stays->total() }}
                </div>
            </div>
            <div class="kt-card-content p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto kt-table">
                        <thead>
                            <tr class="text-sm text-secondary-foreground bg-muted/20">
                                <th class="px-4 py-3 text-left">Sl</th>
                                <th class="px-4 py-3 text-left">Guest</th>
                                <th class="px-4 py-3 text-left">Room</th>
                                <th class="px-4 py-3 text-left">Check-in</th>
                                <th class="px-4 py-3 text-left">Expected check-out</th>
                                <th class="px-4 py-3 text-left">Balance</th>
                                <th class="px-4 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($stays as $stay)
                                @php
                                    $guest = $stay->reservation?->guest;
                                    $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
                                    $guestName = $guestName !== '' ? $guestName : '-';

                                    $roomNumber = $stay->room?->room_number ?? '-';

                                    $checkInDate = $stay->reservation?->check_in_date;
                                    if (!$checkInDate && $stay->check_in_time) {
                                        $checkInDate = $stay->check_in_time;
                                    }

                                    $expectedOut = $stay->reservation?->check_out_date;
                                    $isOverdue = $expectedOut && \Carbon\Carbon::parse($expectedOut)->startOfDay()->lt($today);
                                @endphp

                                <tr class="border-b border-input hover:bg-accent/10 {{ ($view === 'due' && $isOverdue) ? 'bg-destructive/5' : '' }}">
                                    <td class="px-4 py-3 align-top">
                                        {{ (($stays->currentPage() - 1) * $stays->perPage()) + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $guestName }}</span>
                                            @if($view === 'due' && $isOverdue)
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">Overdue</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">{{ $roomNumber }}</td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $checkInDate ? \Carbon\Carbon::parse($checkInDate)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $expectedOut ? \Carbon\Carbon::parse($expectedOut)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">--</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex items-center gap-2">
                                            @if($view === 'checked_out')
                                                <button class="kt-btn kt-btn-sm kt-btn-outline" type="button" disabled>Checked Out</button>
                                            @else
                                                <a class="kt-btn kt-btn-sm kt-btn-primary" href="{{ route('admin.front-desk.departures.check-out', $stay->id) }}">Check-Out</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-6 text-center text-secondary-foreground" colspan="7">
                                        @if($view === 'checked_out')
                                            No check-outs recorded today.
                                        @else
                                            No departures for today.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $stays->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
