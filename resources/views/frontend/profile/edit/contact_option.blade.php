@extends('frontend.layouts.__app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-2xl p-4 sm:p-8 md:p-12 border border-slate-100">
        <form id="proSignupForm"
            action="{{ route('save.seller.profile', ['type' => $user->type, 'setup' => 'contact_option']) }}" method="POST"
            class="space-y-10">
            @csrf

            <div class="step" data-step="3">
                <h2 class="text-3xl font-bold mb-6">How Customers Can Reach You</h2>
                <p class="text-slate-600 mb-10">Choose the contact methods you want visible on your public profile.
                    All interactions are tracked as leads (pay-per-lead).</p>

                <div class="space-y-10">
                    <!-- In-App Messaging -->
                    <div class="bg-gradient-to-r from-slate-900 to-teal-900 p-5 sm:p-10 rounded-3xl text-white shadow-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold mb-4">Zonely Secure Messaging (Recommended)</h3>
                                <p class="text-slate-200 mb-6">Customers message you directly through your profile.
                                    Instant notifications via email & SMS.</p>
                                <ul class="text-base space-y-3 opacity-90">
                                    <li>✓ Completely free</li>
                                    <li>✓ Built-in lead tracking</li>
                                    <li>✓ Professional conversation history</li>
                                    <li>✓ Verified customers only</li>
                                </ul>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer ml-3 sm:ml-8 shrink-0">
                                <input type="checkbox" checked class="sr-only peer">
                                <div
                                    class="w-16 h-9 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-teal-600">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div
                        class="bg-white border-2 border-slate-200 rounded-3xl p-5 sm:p-10 hover:border-teal-700 transition-all shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-6 mb-6">
                                    <div
                                        class="w-16 h-16 bg-teal-100 rounded-3xl flex items-center justify-center text-teal-700 text-3xl">
                                        <i class="fa-solid fa-phone text-2xl"></i></div>
                                    <div>
                                        <h3 class="text-2xl font-bold">Direct Phone Call</h3>
                                        <p class="text-base text-slate-500">Show your phone for instant calls</p>
                                    </div>
                                </div>
                                <input type="tel" placeholder="Public phone number (optional)"
                                    class="w-full px-6 py-5 rounded-3xl border border-slate-300 focus:border-teal-700 focus:ring-4 focus:ring-teal-100 outline-none transition">
                                <p class="text-sm text-slate-500 mt-4">Tracked calls = high-intent paid leads</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer ml-3 sm:ml-8 shrink-0">
                                <input type="checkbox" checked class="sr-only peer">
                                <div
                                    class="w-16 h-9 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-teal-600">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div
                        class="bg-white border-2 border-slate-200 rounded-3xl p-5 sm:p-10 hover:border-emerald-600 transition-all shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-6 mb-6">
                                    <div
                                        class="w-16 h-16 bg-emerald-100 rounded-3xl flex items-center justify-center text-emerald-600 text-3xl">
                                        <i class="fa-brands fa-whatsapp text-2xl"></i></div>
                                    <div>
                                        <h3 class="text-2xl font-bold">WhatsApp Consultation</h3>
                                        <p class="text-base text-slate-500">Fastest growing messaging channel</p>
                                    </div>
                                </div>
                                <input type="tel" placeholder="WhatsApp number (e.g. +1 555 123 4567)"
                                    class="w-full px-6 py-5 rounded-3xl border border-slate-300 focus:border-teal-700 focus:ring-4 focus:ring-teal-100 outline-none transition">
                                <p class="text-sm text-emerald-600 font-medium mt-4">Converts 3x faster than forms
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer ml-3 sm:ml-8 shrink-0">
                                <input type="checkbox" class="sr-only peer">
                                <div
                                    class="w-16 h-9 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-emerald-500">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Zonely Booking -->
                    @if ($user->type === 'seller')
                        <div
                            class="bg-white border-2 border-slate-200 rounded-3xl p-5 sm:p-10 hover:border-indigo-600 transition-all shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-6 mb-6">
                                        <div
                                            class="w-16 h-16 bg-indigo-100 rounded-3xl flex items-center justify-center text-indigo-600 text-3xl">
                                            <i class="fa-solid fa-calendar-check text-2xl"></i></div>
                                        <div>
                                            <h3 class="text-2xl font-bold">Zonely Built-in Booking</h3>
                                            <p class="text-base text-slate-500">Fully integrated appointment scheduling
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-slate-600 mb-8">Customers book directly on your profile.</p>

                                    <div class="space-y-7">
                                        <div>
                                            <label class="block text-lg font-semibold text-slate-800 mb-2">Default
                                                Appointment Duration <span class="text-red-500">*</span></label>
                                            <select id="appointmentDuration"
                                                class="w-full max-w-md px-6 py-5 rounded-3xl border border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 outline-none">
                                                <option value="15">15 minutes</option>
                                                <option value="30" selected>30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60">60 minutes</option>
                                                <option value="90">90 minutes</option>
                                                <option value="120">120 minutes</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-lg font-semibold text-slate-800 mb-2">Buffer
                                                Time</label>
                                            <select id="bufferTime"
                                                class="w-full max-w-md px-6 py-5 rounded-3xl border border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 outline-none">
                                                <option value="0">None</option>
                                                <option value="10">10 minutes</option>
                                                <option value="15" selected>15 minutes</option>
                                                <option value="30">30 minutes</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-lg font-semibold text-slate-800 mb-2">Default
                                                Daily Hours</label>
                                            <div class="grid grid-cols-2 gap-6 max-w-md">
                                                <input type="time" id="startTime" value="09:00"
                                                    class="px-6 py-5 rounded-3xl border border-slate-300 focus:border-indigo-600">
                                                <input type="time" id="endTime" value="18:00"
                                                    class="px-6 py-5 rounded-3xl border border-slate-300 focus:border-indigo-600">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-lg font-semibold text-slate-800 mb-4">Default
                                                Working Days</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="monday" checked
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Monday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="tuesday" checked
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Tuesday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="wednesday" checked
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Wednesday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="thursday" checked
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Thursday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="friday" checked
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Friday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="saturday"
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Saturday</span>
                                                </label>
                                                <label
                                                    class="day-label flex items-center justify-center p-5 bg-slate-50 rounded-3xl border-2 border-slate-200 hover:border-indigo-600 cursor-pointer transition-all">
                                                    <input type="checkbox" name="workingDays" value="sunday"
                                                        class="sr-only peer">
                                                    <span
                                                        class="font-semibold text-slate-700 peer-checked:text-indigo-600 peer-checked:font-bold">Sunday</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-indigo-600 font-medium mt-8">Best conversion method –
                                        customers book instantly!</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-3 sm:ml-8 shrink-0">
                                    <input type="checkbox" checked class="sr-only peer enable-zonely-booking">
                                    <div
                                        class="w-16 h-9 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-indigo-500">
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between mt-16">
                    <button type="button" class="prev-step text-slate-600 font-bold hover:text-slate-900 transition">←
                        Back</button>
                    <button type="button"
                        class="next-step bg-slate-900 text-white px-10 py-5 rounded-3xl font-bold hover:bg-teal-700 transition shadow-lg">Next
                        →</button>
                </div>
            </div>

        </form>
    </div>
@endsection

@section('script')
    <script>
        const nextBtns = document.querySelectorAll('.next-step');

        function validateStep() {
            const required = document.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            required.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            return valid;
        }

        nextBtns.forEach(btn => btn.addEventListener('click', () => {
            if (validateStep()) {
                document.getElementById('proSignupForm').submit();
            } else if (!validateStep()) {
                alert('Please fill all required fields correctly.');
            }
        }));

        document.addEventListener("change", function(e) {

            if (e.target.classList.contains("category-select")) {

                let categoryId = e.target.value;
                let wrapper = document.getElementById("category-wrapper");

                // 🔥 Remove all selects AFTER current one
                let currentDiv = e.target.closest('div');
                let next = currentDiv.nextElementSibling;
                while (next) {
                    next.remove();
                    next = currentDiv.nextElementSibling;
                }

                if (categoryId) {
                    fetch('/get-subcategories/' + categoryId)
                        .then(res => res.json())
                        .then(data => {

                            if (data.length > 0) {

                                let newDiv = document.createElement("div");

                                let options = `<option value="">Select Sub Category</option>`;
                                data.forEach(item => {
                                    options += `<option value="${item.id}">${item.title}</option>`;
                                });

                                newDiv.innerHTML = `<label class="block text-lg font-semibold text-slate-800 mb-3"> Sub Category </label>
                                    <select name="category_id[]"
                                        class="category-select w-full px-6 py-5 rounded-3xl border border-slate-300 focus:border-teal-700 focus:ring-4 focus:ring-teal-100 outline-none">
                                        ${options}
                                    </select>
                                `;
                                wrapper.appendChild(newDiv);
                            }
                        });
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            fetch('/countries')
                .then(res => res.json())
                .then(data => {

                    let countrySelect = document.querySelector('.country-select');

                    let options = `<option value="">Select Country</option>`;
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.title}</option>`;
                    });

                    countrySelect.innerHTML = options;
                });

        });
        document.addEventListener("change", function(e) {

            if (e.target.classList.contains("country-select")) {

                let countryId = e.target.value;
                let block = e.target.closest('.location-block');

                let stateSelect = block.querySelector('.state-select');
                let citySelect = block.querySelector('.city-select');
                let zipSelect = block.querySelector('.zip-select');

                // reset
                stateSelect.innerHTML = `<option value="">Select State</option>`;
                citySelect.innerHTML = `<option value="">Select City</option>`;
                zipSelect.innerHTML = `<option value="">Select Zip</option>`;
                stateSelect.disabled = true;
                citySelect.disabled = true;
                zipSelect.disabled = true;

                if (countryId) {
                    fetch('/states/' + countryId)
                        .then(res => res.json())
                        .then(data => {

                            let options = `<option value="">Select State</option>`;
                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.title}</option>`;
                            });

                            stateSelect.innerHTML = options;
                            stateSelect.disabled = false;
                        });
                }
            }

        });
        document.addEventListener("change", function(e) {

            if (e.target.classList.contains("state-select")) {

                let stateId = e.target.value;
                let block = e.target.closest('.location-block');

                let citySelect = block.querySelector('.city-select');
                let zipSelect = block.querySelector('.zip-select');

                // reset
                citySelect.innerHTML = `<option value="">Select City</option>`;
                zipSelect.innerHTML = `<option value="">Select Zip</option>`;
                citySelect.disabled = true;
                zipSelect.disabled = true;

                if (stateId) {
                    fetch('/cities/' + stateId)
                        .then(res => res.json())
                        .then(data => {

                            let options = `<option value="">Select City</option>`;
                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.title}</option>`;
                            });

                            citySelect.innerHTML = options;
                            citySelect.disabled = false;
                        });
                }
            }

        });

        document.addEventListener("change", function(e) {

            if (e.target.classList.contains("city-select")) {

                let cityId = e.target.value;
                let block = e.target.closest('.location-block');

                let zipSelect = block.querySelector('.zip-select');

                // reset
                zipSelect.innerHTML = `<option value="">Select ZIP Code</option>`;
                zipSelect.disabled = true;

                if (cityId) {
                    fetch('/postal-codes/' + cityId)
                        .then(res => res.json())
                        .then(data => {

                            let options = `<option value="">Select ZIP Code</option>`;

                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.title}</option>`;
                            });

                            zipSelect.innerHTML = options;
                            zipSelect.disabled = false;
                        });
                }
            }

        });
    </script>
@endsection
