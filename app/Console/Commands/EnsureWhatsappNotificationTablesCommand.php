<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EnsureWhatsappNotificationTablesCommand extends Command
{
    protected $signature = 'church:ensure-whatsapp-notification-tables';

    protected $description = 'Membuat tabel notifikasi WhatsApp (WAHA) jika belum ada.';

    public function handle(): int
    {
        try {
            if (! Schema::hasTable('whatsapp_waha_configs')) {
                Schema::create('whatsapp_waha_configs', function (Blueprint $table) {
                    $table->id();
                    $table->string('host', 500)->nullable();
                    $table->text('api_key')->nullable();
                    $table->string('session', 120)->default('default');
                    $table->boolean('is_connected')->default(false);
                    $table->timestamp('last_connected_at')->nullable();
                    $table->timestamps();
                });
                $this->components->info('Tabel whatsapp_waha_configs dibuat.');
            }

            if (! Schema::hasTable('whatsapp_message_templates')) {
                Schema::create('whatsapp_message_templates', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->string('trigger_key')->unique();
                    $table->text('message');
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->timestamps();
                });
                $this->components->info('Tabel whatsapp_message_templates dibuat.');
            }

            if (! Schema::hasTable('whatsapp_notification_recipients')) {
                Schema::create('whatsapp_notification_recipients', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                    $table->string('chat_id', 64);
                    $table->timestamps();
                    $table->unique('user_id');
                });
                $this->components->info('Tabel whatsapp_notification_recipients dibuat.');
            }

            if (! Schema::hasTable('whatsapp_notification_recipient_triggers')) {
                Schema::create('whatsapp_notification_recipient_triggers', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('recipient_id')
                        ->constrained('whatsapp_notification_recipients')
                        ->cascadeOnDelete();
                    $table->string('trigger_key', 120);
                    $table->timestamps();

                    $table->unique(['recipient_id', 'trigger_key'], 'wa_recipient_trigger_unique');
                });
                $this->components->info('Tabel whatsapp_notification_recipient_triggers dibuat.');
            }

            if (! Schema::hasTable('whatsapp_broadcast_templates')) {
                Schema::create('whatsapp_broadcast_templates', function (Blueprint $table) {
                    $table->id();
                    $table->string('trigger_key', 120);
                    $table->string('audience', 40);
                    $table->text('message');
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->timestamps();
                });
                $this->components->info('Tabel whatsapp_broadcast_templates dibuat.');
            }

            if (! Schema::hasTable('whatsapp_broadcast_template_users')) {
                Schema::create('whatsapp_broadcast_template_users', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('broadcast_template_id')
                        ->constrained('whatsapp_broadcast_templates')
                        ->cascadeOnDelete();
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                    $table->string('recipient_name', 200)->nullable();
                    $table->string('recipient_phone', 32)->nullable();
                    $table->timestamps();

                    $table->unique(['broadcast_template_id', 'user_id'], 'wa_broadcast_user_unique');
                });
                $this->components->info('Tabel whatsapp_broadcast_template_users dibuat.');
            } else {
                Schema::table('whatsapp_broadcast_template_users', function (Blueprint $table) {
                    if (! Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_name')) {
                        $table->string('recipient_name', 200)->nullable()->after('user_id');
                    }
                    if (! Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_phone')) {
                        $table->string('recipient_phone', 32)->nullable()->after('recipient_name');
                    }
                });

                try {
                    DB::statement('ALTER TABLE whatsapp_broadcast_template_users MODIFY user_id BIGINT UNSIGNED NULL');
                } catch (Throwable) {
                    // Kolom user_id mungkin sudah nullable atau driver tidak mendukung perintah ini.
                }
            }

            $this->components->info('Tabel notifikasi WhatsApp siap.');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->components->error('Gagal: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
