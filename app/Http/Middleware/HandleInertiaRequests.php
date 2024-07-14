<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'game' => config('game'),
            'currentRoute' => $request->route()->getName(),
            'flash' => [
                'toasts' => $this->getToasts(),
            ],
        ]);
    }

    private function getToasts(): array
    {
        // Get the generic toasts bucket, ensure it's an array.
        $toasts = Arr::wrap(session('toasts', []));
        $types = ['success', 'warning', 'info', 'error', 'message', 'locked', 'question'];

        // Gather any session type-specific toasts (success, error, etc)
        foreach ($types as $type) {
            $toast = session($type);

            if (empty($toast)) {
                continue;
            }

            $toasts[] = is_string($toast)
                ? ['type' => $type, 'title' => $toast]
                : $toast;
        }

        return $toasts;
    }
}
