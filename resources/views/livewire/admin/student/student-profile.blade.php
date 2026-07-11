@section('header', $student->user?->name . ' — প্রোফাইল')

<div class="space-y-6">

    {{-- ── Profile Header ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            {{-- Photo --}}
            <div class="flex-shrink-0">
                @if($student->photo_path)
                    <img src="{{ asset('storage/' . $student->photo_path) }}"
                         class="w-24 h-24 rounded-2xl object-cover border-4 border-indigo-100 shadow-sm"
                         alt="{{ $student->user?->name }}">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-4xl font-bold text-white shadow-sm">
                        {{ mb_substr($student->user?->name ?? '?', 0, 1) }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $student->user?->name ?? '—' }}</h1>
                <div class="flex flex-wrap gap-3 mt-2">
                    <span class="inline-flex items-center gap-1.5 text-sm font-mono font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg">
                        🎫 {{ $student->admission_no }}
                    </span>
                    @if($student->schoolClass)
                        <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                            📚 {{ $student->schoolClass->name }}
                            @if($student->section)
                                — {{ $student->section->name }}
                            @endif
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status_color }}">
                        {{ $student->status_label }}
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.students.edit', $student->id) }}"
                   class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium">
                    ✏️ এডিট
                </a>
                <a href="{{ route('admin.students') }}"
                   class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition">
                    ← ফিরে যান
                </a>
            </div>
        </div>
    </div>

    {{-- ── Tabs ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Tab nav --}}
        <div class="border-b border-gray-100 flex overflow-x-auto scrollbar-hide">
            @php
                $tabs = [
                    'personal'  => ['label' => 'ব্যক্তিগত তথ্য', 'icon' => '👤'],
                    'guardian'  => ['label' => 'অভিভাবক', 'icon' => '👨‍👩‍👧'],
                    'academic'  => ['label' => 'শিক্ষাগত তথ্য', 'icon' => '📚'],
                    'fees'      => ['label' => 'ফি তথ্য', 'icon' => '💳'],
                    'attendance' => ['label' => 'উপস্থিতি', 'icon' => '📋'],
                ];
            @endphp
            @foreach($tabs as $key => $tab)
                <button wire:click="setTab('{{ $key }}')"
                        @class([
                            'flex items-center gap-2 px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition',
                            'border-indigo-600 text-indigo-600 bg-indigo-50/50' => $activeTab === $key,
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' => $activeTab !== $key,
                        ])>
                    <span>{{ $tab['icon'] }}</span> {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Tab content --}}
        <div class="p-6">

            {{-- ════ PERSONAL TAB ════ --}}
            @if($activeTab === 'personal')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5 text-sm">
                    @php
                        $fields = [
                            'জন্ম তারিখ'         => $student->date_birth?->format('d/m/Y') ?? '—',
                            'লিঙ্গ'               => match($student->gender) {'male'=>'ছেলে','female'=>'মেয়ে','other'=>'অন্যান্য',default=>'—'},
                            'রক্তের গ্রুপ'       => $student->blood_group ?? '—',
                            'জাতীয়তা'           => $student->nationality ?? '—',
                            'জন্ম সনদ নম্বর'     => $student->birth_certificate_no ?? '—',
                            'ইমেইল'              => $student->user?->email ?? '—',
                            'বর্তমান ঠিকানা'     => $student->address_present ?? '—',
                            'স্থায়ী ঠিকানা'      => $student->address_permanent ?? '—',
                            'স্বাস্থ্যগত তথ্য'   => $student->medical_info ?? '—',
                        ];
                    @endphp

                    @foreach($fields as $label => $value)
                        <div class="border-b border-gray-50 pb-3">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">{{ $label }}</p>
                            <p class="text-gray-800 font-medium">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

            {{-- ════ GUARDIAN TAB ════ --}}
            @elseif($activeTab === 'guardian')
                @forelse($student->guardians as $guardian)
                    <div class="border border-gray-100 rounded-xl p-5 mb-4 last:mb-0">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">
                                {{ mb_substr($guardian->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $guardian->name }}</h3>
                                <p class="text-xs text-gray-400">{{ $guardian->pivot->relation }} @if($guardian->pivot->is_primary) · <span class="text-indigo-500 font-medium">প্রধান অভিভাবক</span> @endif</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-400 text-xs block">ফোন</span> {{ $guardian->phone ?? '—' }}</div>
                            <div><span class="text-gray-400 text-xs block">ইমেইল</span> {{ $guardian->email ?? '—' }}</div>
                            <div><span class="text-gray-400 text-xs block">পেশা</span> {{ $guardian->occupation ?? '—' }}</div>
                            <div><span class="text-gray-400 text-xs block">মাতার নাম</span> {{ $guardian->mother_name ?? '—' }}</div>
                            @if($guardian->address)
                                <div class="col-span-2"><span class="text-gray-400 text-xs block">ঠিকানা</span> {{ $guardian->address }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <div class="text-4xl mb-3">👨‍👩‍👧</div>
                        <p class="text-sm">কোনো অভিভাবক তথ্য পাওয়া যায়নি।</p>
                    </div>
                @endforelse

            {{-- ════ ACADEMIC TAB ════ --}}
            @elseif($activeTab === 'academic')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5 text-sm">
                    @php
                        $academicFields = [
                            'শিক্ষাবর্ষ'       => $student->academicYear?->name ?? '—',
                            'শ্রেণী'            => $student->schoolClass?->name ?? '—',
                            'শাখা'              => $student->section?->name ?? '—',
                            'রোল নম্বর'         => $student->roll_no ?? '—',
                            'ভর্তি নম্বর'       => $student->admission_no,
                        ];
                    @endphp

                    @foreach($academicFields as $label => $value)
                        <div class="border-b border-gray-50 pb-3">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">{{ $label }}</p>
                            <p class="text-gray-800 font-semibold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

            {{-- ════ FEES TAB ════ --}}
            @elseif($activeTab === 'fees')
                <div class="text-center py-10 text-gray-400">
                    <div class="text-4xl mb-3">💳</div>
                    <p class="text-sm font-medium text-gray-500">ফি মডিউল শীঘ্রই আসছে</p>
                    <p class="text-xs mt-1">Fees module will be integrated in the next phase.</p>
                </div>

            {{-- ════ ATTENDANCE TAB ════ --}}
            @elseif($activeTab === 'attendance')
                <div class="text-center py-10 text-gray-400">
                    <div class="text-4xl mb-3">📋</div>
                    <p class="text-sm font-medium text-gray-500">উপস্থিতি মডিউল শীঘ্রই আসছে</p>
                    <p class="text-xs mt-1">Attendance module will be integrated in the next phase.</p>
                </div>
            @endif

        </div>
    </div>

</div>
