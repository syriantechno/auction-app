<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('auction.{auctionId}', function ($user, $auctionId) {
    // In a real app, check if user matches certain criteria (e.g., has deposit)
    return true; 
});

// ── Real-time Notification Channels ─────────────────────────────

// Private channel for user notifications
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// ── Leads Real-time Channels ─────────────────────────────
// For secretaries, admins, and super admins to receive new leads instantly

Broadcast::channel('leads.admin', function ($user) {
    return $user->can('view leads') || $user->hasRole(['admin', 'super-admin', 'secretary']);
});

Broadcast::channel('leads.secretary', function ($user) {
    return $user->hasRole(['secretary', 'super-admin', 'admin']);
});

Broadcast::channel('leads.super', function ($user) {
    return $user->hasRole('super-admin');
});
