@php
    $isCreate = ! isset($user) || $user === null;
@endphp
<div class="space-y-6">
    <fieldset class="space-y-4">
        <x-admin-field-label as="legend">Identitas</x-admin-field-label>
        <div>
            <x-admin-field-label>Nama</x-admin-field-label>
            <input name="name" value="{{ old('name', $user?->name) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <x-admin-field-label>Email</x-admin-field-label>
            <input type="email" name="email" value="{{ old('email', $user?->email) }}" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <x-admin-field-label>Peran</x-admin-field-label>
            <select name="role" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected(old('role', $user?->role ?? \App\Models\User::ROLE_ADMIN) === \App\Models\User::ROLE_ADMIN)>Admin</option>
                <option value="{{ \App\Models\User::ROLE_SUPER_ADMIN }}" @selected(old('role', $user?->role) === \App\Models\User::ROLE_SUPER_ADMIN)>Super admin</option>
            </select>
            <p class="mt-1 text-sm text-slate-400">Admin tidak dapat mengelola halaman CMS dan akun pengurus. Super admin memiliki akses penuh.</p>
            @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </fieldset>
    <fieldset class="space-y-4">
        <x-admin-field-label as="legend">Kata sandi</x-admin-field-label>
        @if (! $isCreate)
            <p class="text-sm text-slate-400">Kosongkan jika tidak ingin mengubah kata sandi.</p>
        @endif
        <div>
            <x-admin-field-label>Kata sandi{{ $isCreate ? '' : ' baru' }}</x-admin-field-label>
            <input type="password" name="password" {{ $isCreate ? 'required' : '' }} autocomplete="new-password" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <x-admin-field-label>Konfirmasi kata sandi</x-admin-field-label>
            <input type="password" name="password_confirmation" {{ $isCreate ? 'required' : '' }} autocomplete="new-password" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
        </div>
    </fieldset>
</div>
