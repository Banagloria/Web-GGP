<div class="space-y-4">
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Judul'])
        <input name="title" value="{{ old('title', $announcement?->title) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Isi'])
        <textarea name="body" rows="10" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">{{ old('body', $announcement?->body) }}</textarea>
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Tanggal tayang'])
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($announcement?->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
    </label>
    <label class="block">
        @include('admin.partials.form-label', ['text' => 'Status'])
        <select name="is_published" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            <option value="0" @selected(old('is_published', $announcement?->is_published ? '1' : '0') == '0')>Draft</option>
            <option value="1" @selected(old('is_published', $announcement?->is_published ? '1' : '0') == '1')>Publish</option>
        </select>
        @error('is_published')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </label>
</div>
