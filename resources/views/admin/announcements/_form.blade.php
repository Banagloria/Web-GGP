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
</div>
