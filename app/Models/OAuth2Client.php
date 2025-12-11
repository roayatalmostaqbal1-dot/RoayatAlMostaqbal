<?php

namespace App\Models;

use Laravel\Passport\Client;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Custom OAuth2 Client Model
 *
 * Extends Laravel Passport Client to fix issues with redirect_uris and grant_types
 * casting and attribute handling.
 */
class OAuth2Client extends Client
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
        'revoked' => 'bool',
    ];

    /**
     * Get the redirect URIs as an array.
     *
     * Override Passport's redirectUris attribute to handle JSON properly.
     */
    protected function redirectUris(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): array {
                if (empty($value)) {
                    return [];
                }

                // Try to decode as JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }

                // If it's a plain string, wrap it in an array
                return [$value];
            },
            set: function ($value): string {
                if (is_array($value)) {
                    return json_encode($value);
                }
                return json_encode([$value]);
            }
        );
    }

    /**
     * Get the grant types as an array.
     *
     * Override Passport's grantTypes attribute to handle JSON properly.
     */
    protected function grantTypes(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): array {
                if (empty($value)) {
                    return [];
                }

                // Try to decode as JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }

                // If it's a plain string, wrap it in an array
                return [$value];
            },
            set: function ($value): string {
                if (is_array($value)) {
                    return json_encode($value);
                }
                if (is_null($value) || $value === '') {
                    return '';
                }
                return json_encode([$value]);
            }
        );
    }
}

