<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\AuditLog;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'clients',
                'create',
                $client,
                null,
                null,
                $client->only(['first_name', 'last_name', 'email', 'phone', 'company_name', 'status'])
            );
        }
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        if (auth()->check()) {
            $changes = $client->getChanges();
            
            if (!empty($changes)) {
                AuditLog::log(
                    'clients',
                    'update',
                    $client,
                    null,
                    $client->getOriginal(),
                    $changes
                );
            }
        }
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'clients',
                'delete',
                $client,
                null,
                $client->only(['first_name', 'last_name', 'email', 'company_name']),
                null
            );
        }
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        if (auth()->check()) {
            AuditLog::log(
                "clients",
                "restore",
                $client,
                "Client {$client->first_name} {$client->last_name} restored",
                null,
                null
            );
        }
    }
}
