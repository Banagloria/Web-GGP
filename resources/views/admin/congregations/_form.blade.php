<div class="space-y-4">
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Nama lengkap'])
        <input name="full_name" value="{{ old('full_name', $registration?->full_name) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        @error('full_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Tempat lahir'])
            <input name="birth_place" value="{{ old('birth_place', $registration?->birth_place) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </label>
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Tanggal lahir'])
            <input type="date" name="birth_date" value="{{ old('birth_date', optional($registration?->birth_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </label>
    </div>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Jenis kelamin'])
        <select name="gender" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            <option value="">—</option>
            @foreach (['Laki-laki'=>'Laki-laki','Perempuan'=>'Perempuan'] as $val => $lab)
                <option value="{{ $val }}" @selected(old('gender', $registration?->gender) === $val)>{{ $lab }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Alamat'])
        <textarea name="address" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">{{ old('address', $registration?->address) }}</textarea>
    </label>
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Telepon'])
            <input name="phone" value="{{ old('phone', $registration?->phone) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </label>
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Email'])
            <input type="email" name="email" value="{{ old('email', $registration?->email) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </label>
    </div>
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
