<?php

namespace App\Services;

/**
 * Keeps the existing school kiosk on the browser engine contract.
 */
class LegacyKioskFaceEngineService extends KioskFaceEngineService
{
    public function driver(): string
    {
        return 'browser';
    }

    public function usesPython(): bool
    {
        return false;
    }

    public function displayLabel(): string
    {
        return 'Browser Face API';
    }
}
