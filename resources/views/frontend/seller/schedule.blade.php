@extends('frontend.layouts.__prof_app')
@section('title', 'Booking Schedule')
@section('content')
<div class="pb-10">
    <div class="max-w-2xl mx-auto py-6 px-4 lg:px-0">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Booking Schedule</h1>
            <p class="text-sm text-slate-500 mt-0.5">Set when you're available so clients can book time slots</p>
        </div>

        @if(session('success'))
            <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-2xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('seller.schedule.update') }}" method="POST" id="scheduleForm">
            @csrf

            {{-- Working Days --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-week text-teal-700 text-sm"></i> Working Days
                </h2>
                <div class="grid grid-cols-4 sm:grid-cols-7 gap-1.5 sm:gap-2">
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $i => $day)
                    <label class="day-toggle cursor-pointer">
                        <input type="checkbox" name="working_days[]" value="{{ strtolower($day) }}"
                            {{ in_array(strtolower($day), $schedule['working_days'] ?? ['mon','tue','wed','thu','fri']) ? 'checked' : '' }}
                            class="sr-only peer" onchange="updatePreview()">
                        <div class="text-center py-3.5 rounded-2xl border-2 border-slate-200 text-sm font-bold text-slate-400
                                    peer-checked:bg-teal-700 peer-checked:border-teal-700 peer-checked:text-white
                                    hover:border-teal-300 hover:text-teal-600 transition select-none">
                            {{ $day }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Time Slots by Period --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-teal-700 text-sm"></i> Time Slots
                </h2>
                <p class="text-xs text-slate-500 mb-5">Define your available time periods. Each period generates bookable slots automatically.</p>

                <div class="space-y-4" id="periodsContainer">

                    @foreach($schedule['periods'] ?? [['label'=>'Morning','from'=>'09:00','to'=>'12:00'],['label'=>'Afternoon','from'=>'13:00','to'=>'17:00']] as $i => $period)
                    <div class="period-block bg-slate-50 rounded-2xl border border-slate-200 p-4" data-index="{{ $i }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="period-label-badge text-xs font-bold px-3 py-1 rounded-full
                                    {{ $period['label'] === 'Morning' ? 'bg-amber-100 text-amber-700' : ($period['label'] === 'Afternoon' ? 'bg-teal-100 text-teal-800' : 'bg-purple-100 text-purple-700') }}">
                                    {{ $period['label'] }}
                                </span>
                                <input type="text" name="periods[{{ $i }}][label]" value="{{ $period['label'] }}"
                                    placeholder="Period name"
                                    class="text-sm font-semibold text-slate-700 bg-transparent border-0 focus:outline-none focus:bg-white focus:border focus:border-slate-200 focus:rounded-xl px-2 py-1 w-32">
                            </div>
                            <button type="button" onclick="removePeriod(this)" class="text-slate-300 hover:text-red-400 transition text-sm">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1 block">From</label>
                                <input type="time" name="periods[{{ $i }}][from]" value="{{ $period['from'] }}" onchange="updatePreview()"
                                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1 block">To</label>
                                <input type="time" name="periods[{{ $i }}][to]" value="{{ $period['to'] }}" onchange="updatePreview()"
                                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="text-xs font-semibold text-slate-500 mb-1 block">Slot Duration</label>
                                <select name="periods[{{ $i }}][duration]" onchange="updatePreview()"
                                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                                    @foreach([15=>'15 min',30=>'30 min',45=>'45 min',60=>'1 hour',90=>'1.5 hours',120=>'2 hours'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ ($period['duration'] ?? 60) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="text-xs font-semibold text-slate-500 mb-1 block">Buffer</label>
                                <select name="periods[{{ $i }}][buffer]"
                                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                                    @foreach([0=>'None',10=>'10 min',15=>'15 min',30=>'30 min'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ ($period['buffer'] ?? 0) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <button type="button" onclick="addPeriod()" class="mt-4 w-full py-3 rounded-2xl border-2 border-dashed border-slate-200 text-sm font-bold text-slate-400 hover:border-teal-300 hover:text-teal-700 transition">
                    <i class="fa-solid fa-plus mr-1"></i> Add Time Period
                </button>
            </div>

            {{-- Slot Preview --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-teal-700 text-sm"></i> Slot Preview
                </h2>
                <p class="text-xs text-slate-500 mb-4">How your available slots look to buyers</p>
                <div id="slotPreview" class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">9:00 AM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">10:00 AM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">11:00 AM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold border border-slate-200 line-through">12:00 PM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">1:00 PM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">2:00 PM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">3:00 PM</span>
                    <span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">4:00 PM</span>
                </div>
                <p class="text-xs text-slate-400 mt-3">
                    <span class="inline-block w-3 h-3 rounded bg-teal-50 border border-teal-100 mr-1 align-middle"></span>Available &nbsp;
                    <span class="inline-block w-3 h-3 rounded bg-slate-100 border border-slate-200 mr-1 align-middle"></span>Buffer/Booked
                </p>
            </div>

            {{-- Advance Booking & Max per Day --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-teal-700 text-sm"></i> Booking Rules
                </h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max bookings per day</label>
                        <select name="max_per_day" class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            @foreach([1,2,3,4,5,6,8,10] as $n)
                            <option value="{{ $n }}" {{ ($schedule['max_per_day'] ?? 4) == $n ? 'selected' : '' }}>{{ $n }} bookings</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Advance booking window</label>
                        <select name="advance_days" class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            @foreach([7=>'1 week',14=>'2 weeks',30=>'1 month',60=>'2 months',90=>'3 months'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ ($schedule['advance_days'] ?? 30) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Minimum notice</label>
                        <select name="min_notice_hours" class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            @foreach([1=>'1 hour',2=>'2 hours',4=>'4 hours',12=>'12 hours',24=>'1 day',48=>'2 days'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ ($schedule['min_notice_hours'] ?? 2) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Booking type</label>
                        <select name="booking_type" class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                            <option value="instant" {{ ($schedule['booking_type'] ?? '') === 'instant' ? 'selected' : '' }}>Instant confirmation</option>
                            <option value="manual" {{ ($schedule['booking_type'] ?? '') === 'manual' ? 'selected' : '' }}>Manual approval</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-2xl text-base transition">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Schedule
            </button>
        </form>

    </div>
</div>

<script>
let periodCount = {{ count($schedule['periods'] ?? [['a','b']]) }};

function addPeriod() {
    const i = periodCount++;
    const labels = ['Morning','Afternoon','Evening','Custom'];
    const label = labels[i % labels.length];
    const colors = {'Morning':'bg-amber-100 text-amber-700','Afternoon':'bg-teal-100 text-teal-800','Evening':'bg-purple-100 text-purple-700','Custom':'bg-slate-100 text-slate-700'};
    const defaults = {'Morning':['09:00','12:00'],'Afternoon':['13:00','17:00'],'Evening':['18:00','21:00'],'Custom':['08:00','18:00']};
    const [from,to] = defaults[label] || ['09:00','17:00'];
    const html = `
    <div class="period-block bg-slate-50 rounded-2xl border border-slate-200 p-4" data-index="${i}">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="period-label-badge text-xs font-bold px-3 py-1 rounded-full ${colors[label]}">${label}</span>
                <input type="text" name="periods[${i}][label]" value="${label}" placeholder="Period name"
                    class="text-sm font-semibold text-slate-700 bg-transparent border-0 focus:outline-none focus:bg-white focus:border focus:border-slate-200 focus:rounded-xl px-2 py-1 w-32">
            </div>
            <button type="button" onclick="removePeriod(this)" class="text-slate-300 hover:text-red-400 transition text-sm">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">From</label>
                <input type="time" name="periods[${i}][from]" value="${from}" onchange="updatePreview()"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">To</label>
                <input type="time" name="periods[${i}][to]" value="${to}" onchange="updatePreview()"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Slot Duration</label>
                <select name="periods[${i}][duration]" onchange="updatePreview()"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                    <option value="15">15 min</option>
                    <option value="30">30 min</option>
                    <option value="45">45 min</option>
                    <option value="60" selected>1 hour</option>
                    <option value="90">1.5 hours</option>
                    <option value="120">2 hours</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Buffer</label>
                <select name="periods[${i}][buffer]"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 bg-white">
                    <option value="0">None</option>
                    <option value="10">10 min</option>
                    <option value="15">15 min</option>
                    <option value="30">30 min</option>
                </select>
            </div>
        </div>
    </div>`;
    document.getElementById('periodsContainer').insertAdjacentHTML('beforeend', html);
}

function removePeriod(btn) {
    const block = btn.closest('.period-block');
    if (document.querySelectorAll('.period-block').length <= 1) {
        alert('Need at least one time period.');
        return;
    }
    if (!confirm('Remove this time period?')) return;
    block.remove();
    updatePreview();
}

function updatePreview() {
    const slots = [];
    document.querySelectorAll('.period-block').forEach(block => {
        const from = block.querySelector('[name*="[from]"]')?.value;
        const to = block.querySelector('[name*="[to]"]')?.value;
        const dur = parseInt(block.querySelector('[name*="[duration]"]')?.value || 60);
        if (!from || !to) return;
        let [fh,fm] = from.split(':').map(Number);
        let [th,tm] = to.split(':').map(Number);
        let cur = fh * 60 + fm;
        const end = th * 60 + tm;
        while (cur + dur <= end) {
            const h = Math.floor(cur/60), m = cur%60;
            const label = (h>12?h-12:h||12)+':'+(m<10?'0'+m:m)+' '+(h>=12?'PM':'AM');
            slots.push(label);
            cur += dur;
        }
    });
    const preview = document.getElementById('slotPreview');
    if (!slots.length) { preview.innerHTML = '<p class="text-xs text-slate-400">No slots generated yet.</p>'; return; }
    preview.innerHTML = slots.map(s =>
        `<span class="px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 text-xs font-bold border border-teal-100">${s}</span>`
    ).join('');
}
</script>
@endsection
