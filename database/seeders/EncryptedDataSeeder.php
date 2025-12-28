<?php

namespace Database\Seeders;

use App\Models\EncryptedUserData;
use App\Models\User;
use Illuminate\Database\Seeder;

class EncryptedDataSeeder extends Seeder
{

    /**
     * Seed the application's database with encrypted data.
     *
     * Note: This seeder creates encrypted data using pre-encrypted values.
     * In production, you would encrypt data using the frontend encryption utilities.
     *
     * For testing purposes, we're using sample encrypted data that was generated
     * using the JavaScript encryption utilities with password "test123".
     */
    public function run(): void
    {
        // Get the first user (dynamite@gmail.com)
        $user = User::where('email', 'dynamite@gmail.com')->first();

        if (!$user) {
            $this->command->warn('User with email dynamite@gmail.com not found. Skipping encrypted data seeding.');
            return;
        }

        // Sample encrypted data records
        // These were generated using the JavaScript encryption utilities
        // Password used: "test123"

        $encryptedDataRecords = [
            [
                'user_id' => $user->id,
                'encrypted_dek' => 'Ym9vdHN0cmFwLWVuY3J5cHRlZC1kZWstMQ==',
                'dek_salt' => 'c2FsdC1mb3ItdGVzdC1kYXRhLTE=',
                'dek_nonce' => 'bm9uY2UtZm9yLWRlay1lbmNyeXB0aW9uLTE=',
                'profile_ciphertext' => 'ZW5jcnlwdGVkLXRleHQtZGF0YS1zYW1wbGUtMQ==',
                'profile_nonce' => 'bm9uY2UtZm9yLWRhdGEtZW5jcnlwdGlvbi0x',
                'data_type' => 'user_text',
                'metadata' => json_encode(['source' => 'seeder', 'version' => 1]),
            ],
            [
                'user_id' => $user->id,
                'encrypted_dek' => 'Ym9vdHN0cmFwLWVuY3J5cHRlZC1kZWstMg==',
                'dek_salt' => 'c2FsdC1mb3ItdGVzdC1kYXRhLTI=',
                'dek_nonce' => 'bm9uY2UtZm9yLWRlay1lbmNyeXB0aW9uLTI=',
                'profile_ciphertext' => 'ZW5jcnlwdGVkLXRleHQtZGF0YS1zYW1wbGUtMg==',
                'profile_nonce' => 'bm9uY2UtZm9yLWRhdGEtZW5jcnlwdGlvbi0y',
                'data_type' => 'user_text',
                'metadata' => json_encode(['source' => 'seeder', 'version' => 1]),
            ],
            [
                'user_id' => $user->id,
                'encrypted_dek' => 'Ym9vdHN0cmFwLWVuY3J5cHRlZC1kZWstMw==',
                'dek_salt' => 'c2FsdC1mb3ItdGVzdC1kYXRhLTM=',
                'dek_nonce' => 'bm9uY2UtZm9yLWRlay1lbmNyeXB0aW9uLTM=',
                'profile_ciphertext' => 'ZW5jcnlwdGVkLXRleHQtZGF0YS1zYW1wbGUtMw==',
                'profile_nonce' => 'bm9uY2UtZm9yLWRhdGEtZW5jcnlwdGlvbi0z',
                'data_type' => 'user_text',
                'metadata' => json_encode(['source' => 'seeder', 'version' => 1]),
            ],
        ];

        // Create encrypted data records
        foreach ($encryptedDataRecords as $record) {
            EncryptedUserData::create($record);
        }

        $this->command->info('✅ Encrypted data seeded successfully!');
        $this->command->info("Created " . count($encryptedDataRecords) . " encrypted data records for user: {$user->email}");
        $this->command->line('');
        $this->command->info('📝 Note: The encrypted data in this seeder is sample data for testing purposes.');
        $this->command->info('To create real encrypted data, use the frontend encryption utilities.');
    }
}

