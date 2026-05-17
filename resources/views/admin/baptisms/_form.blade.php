<div class="space-y-4">
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Nama lengkap'])
        <input name="full_name" value="{{ old('full_name', $registration?->full_name) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-church-navy-mid focus:ring-church-navy-mid">
        @error('full_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Usia'])
            <input type="number" name="age" value="{{ old('age', $registration?->age) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            @error('age')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </label>
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Jenis kelamin'])
            <select name="gender" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                <option value="">—</option>
                @foreach (['Laki-laki'=>'Laki-laki','Perempuan'=>'Perempuan'] as $val => $lab)
                    <option value="{{ $val }}" @selected(old('gender', $registration?->gender) === $val)>{{ $lab }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Tanggal baptis'])
        <input type="date" name="baptism_date" value="{{ old('baptism_date', optional($registration?->baptism_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        @error('baptism_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Status'])
        <select name="status" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            @foreach (['submitted'=>'Diajukan','active'=>'Aktif','rejected'=>'Ditolak','archived'=>'Arsip'] as $val => $lab)
                <option value="{{ $val }}" @selected(old('status', $registration?->status ?? 'submitted') === $val)>{{ $lab }}</option>
            @endforeach
        </select>
        @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Catatan'])
        <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">{{ old('notes', $registration?->notes) }}</textarea>
    </label>
</div>
