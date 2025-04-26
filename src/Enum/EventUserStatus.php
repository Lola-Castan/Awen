<?php

namespace App\Enum;

enum EventUserStatus: string
{
    case ORGANIZER = 'organizer';
    case PARTICIPANT = 'participant';
    case INTERESTED = 'interested';
    case INVITED = 'invited';      // Pour une évolution future
    case DECLINED = 'declined';    // Pour une évolution future

    /**
     * Retourne une représentation humainement lisible du statut
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ORGANIZER => 'Organisateur',
            self::PARTICIPANT => 'Participant',
            self::INTERESTED => 'Intéressé',
            self::INVITED => 'Invité',
            self::DECLINED => 'A décliné',
        };
    }

    /**
     * Liste des statuts qui impliquent une participation active
     */
    public static function getActiveStatuses(): array
    {
        return [self::ORGANIZER, self::PARTICIPANT];
    }

    /**
     * Détermine si le statut implique une participation active
     */
    public function isActiveParticipation(): bool
    {
        return in_array($this, self::getActiveStatuses());
    }
}