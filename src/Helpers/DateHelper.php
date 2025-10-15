<?php

namespace ShopFlow\Helpers;

class DateHelper
{
    public static function formatDate(\DateTime $date, string $format = 'Y-m-d'): string
    {
        return $date->format($format);
    }

    public static function formatDateTime(\DateTime $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function isOverdue(\DateTime $deadline): bool
    {
        return $deadline < new \DateTime();
    }

    public static function getDaysUntil(\DateTime $date): int
    {
        $now = new \DateTime();
        $interval = $now->diff($date);
        return (int)$interval->format('%r%a');
    }

    public static function getRelativeTime(\DateTime $date): string
    {
        $now = new \DateTime();
        $diff = $now->diff($date);
        
        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        }
        if ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        }
        if ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        }
        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        }
        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        }
        
        return 'just now';
    }
}
