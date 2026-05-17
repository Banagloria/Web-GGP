<div class="space-y-4">
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Nama mempelai pria'])
        <input name="groom_name" value="{{ old('groom_name', $registration?->groom_name) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        @error('groom_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Nama mempelai wanita'])
        <input name="bride_name" value="{{ old('bride_name', $registration?->bride_name) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        @error('bride_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </label>
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Tanggal rencana'])
            <input type="date" name="wedding_date" value="{{ old('wedding_date', optional($registration?->wedding_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </label>
        <label class="block">
            @include('admin.partials.form-label', ['text' => 'Telepon'])
            <input name="phone" value="{{ old('phone', $registration?->phone) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
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
