<?php

namespace App\Enum;

/**
 * Enum représentant les différents statuts possibles d'un événement.
 */
enum EventStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
    case Cancelled = 'cancelled';
    
    /**
     * Retourne une représentation lisible du statut
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Draft => 'Brouillon',
            self::Published => 'Publié',
            self::Archived => 'Archivé',
            self::Cancelled => 'Annulé',
        };
    }
    
    /**
     * Vérifie si l'événement est visible pour le public
     */
    public function isVisible(): bool
    {
        return $this === self::Published;
    }
    
    /**
     * Vérifie si l'événement est annulé
     */
    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }
}