<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CafeTable;

class ValidateTableSession
{
    public function handle(Request $request, Closure $next)
    {
        $tableId = $request->input('table_id');

        if (!$tableId) {
            return response()->json([
                'success' => false,
                'message' => 'session_expired',
            ], 401);
        }

        $sessionKey  = 'table_session_' . $tableId;
        $sessionData = session($sessionKey);

        if (!$sessionData || now()->timestamp > $sessionData['expires_at']) {
            session()->forget($sessionKey);
            \App\Models\Order::where('table_id', $tableId)
                ->where('status', 'draft')
                ->each(function ($order) {
                    $order->orderDetails()->delete();
                    $order->delete();
                });
            return response()->json([
                'success' => false,
                'message' => 'session_expired',
            ], 401);
        }

        return $next($request);
    }
}
