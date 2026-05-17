<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('type_slug', 80)->index();
            $table->string('card_key', 80)->index();
            $table->string('status', 32)->default('submitted')->index();
            $table->text('notes')->nullable();
            $table->json('payload');
            $table->json('files')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('congregation_registrations')) {
        foreach (DB::table('congregation_registrations')->orderBy('id')->cursor() as $row) {
            DB::table('registration_submissions')->insert([
                'type_slug' => 'jemaat',
                'card_key' => 'jemaat',
                'status' => $row->status ?? 'submitted',
                'notes' => $row->notes,
                'payload' => json_encode([
                    'full_name' => $row->full_name,
                    'birth_date' => $row->birth_date,
                    'birth_place' => $row->birth_place,
                    'gender' => $row->gender,
                    'address' => $row->address,
                    'phone' => $row->phone,
                    'email' => $row->email,
                ], JSON_UNESCAPED_UNICODE),
                'files' => null,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
        }

        if (Schema::hasTable('baptism_registrations')) {
        foreach (DB::table('baptism_registrations')->orderBy('id')->cursor() as $row) {
            DB::table('registration_submissions')->insert([
                'type_slug' => 'baptisan',
                'card_key' => 'baptis',
                'status' => $row->status ?? 'submitted',
                'notes' => $row->notes,
                'payload' => json_encode([
                    'full_name' => $row->full_name,
                    'age' => $row->age,
                    'gender' => $row->gender,
                    'baptism_date' => $row->baptism_date,
                ], JSON_UNESCAPED_UNICODE),
                'files' => null,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
        }

        if (Schema::hasTable('marriage_registrations')) {
        foreach (DB::table('marriage_registrations')->orderBy('id')->cursor() as $row) {
            DB::table('registration_submissions')->insert([
                'type_slug' => 'pernikahan',
                'card_key' => 'nikah',
                'status' => $row->status ?? 'submitted',
                'notes' => $row->notes,
                'payload' => json_encode([
                    'groom_name' => $row->groom_name,
                    'bride_name' => $row->bride_name,
                    'wedding_date' => $row->wedding_date,
                    'phone' => $row->phone,
                ], JSON_UNESCAPED_UNICODE),
                'files' => null,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_submissions');
    }
};
