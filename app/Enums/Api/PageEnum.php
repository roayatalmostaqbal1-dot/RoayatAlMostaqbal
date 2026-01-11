<?php

namespace App\Enums\Api;

enum PageEnum: string
{
    case DASHBOARD = 'dashboard';
    case USERS = 'users';
    case ROLES = 'roles';
    case PERMISSIONS = 'permissions';
    case PAGES = 'pages';
    case OAUTH2_CLIENTS = 'oauth2-clients';
    case CONTACTS = 'contacts';
    case SETTINGS = 'settings';
    case ENCRYPTED_DATA = 'encrypted-data';
    case ENCRYPTED_DATA_RECOVERY = 'encrypted-data-recovery';
    case TELEGRAM_CHATS = 'telegram-chats';
    case TELEGRAM_MESSAGES = 'telegram-messages';
    case TELEGRAM_BOT = 'telegram-bot';

    public function label(): string
    {
        return match ($this) {
            self::DASHBOARD => 'Dashboard',
            self::USERS => 'Users',
            self::ROLES => 'Roles',
            self::PERMISSIONS => 'Permissions',
            self::PAGES => 'Pages',
            self::OAUTH2_CLIENTS => 'OAuth2 Clients',
            self::CONTACTS => 'Contacts',
            self::SETTINGS => 'Settings',
            self::ENCRYPTED_DATA => 'Encrypted Data',
            self::ENCRYPTED_DATA_RECOVERY => 'Data Recovery',
            self::TELEGRAM_CHATS => 'Telegram Chats',
            self::TELEGRAM_MESSAGES => 'Telegram Messages',
            self::TELEGRAM_BOT => 'Telegram Bot',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DASHBOARD => 'View system dashboard and statistics',
            self::USERS => 'Manage system users, their roles and permissions',
            self::ROLES => 'Manage user roles and their permissions',
            self::PERMISSIONS => 'Manage system permissions',
            self::PAGES => 'Manage page access control and permissions',
            self::OAUTH2_CLIENTS => 'Manage OAuth2 clients',
            self::CONTACTS => 'View and manage contact submissions',
            self::SETTINGS => 'System settings and configuration',
            self::ENCRYPTED_DATA => 'Manage encrypted user data',
            self::ENCRYPTED_DATA_RECOVERY => 'Recover encrypted user data (Admin only)',
            self::TELEGRAM_CHATS => 'View and manage Telegram chats',
            self::TELEGRAM_MESSAGES => 'View and manage Telegram messages',
            self::TELEGRAM_BOT => 'View and manage Telegram bot',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => [
            'key' => $case->value,
            'name' => $case->label(),
            'description' => $case->description(),
        ], self::cases());
    }

    public static function keys(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
