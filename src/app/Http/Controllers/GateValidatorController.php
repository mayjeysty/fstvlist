<?php

namespace App\Http\Controllers;

use App\Services\QrValidationService;
use Illuminate\Http\Request;
use Throwable;

class GateValidatorController extends Controller
{
    public function __construct(private QrValidationService $qrService) {}

    public function index()
    {
        return view('gate.index');
    }

    public function validate(Request $request)
    {
        $request->validate(['qr_token' => ['required', 'string']]);

        try {
            $ticket = $this->qrService->validate($request->qr_token, auth()->id());

            return back()->with('success', [
                'message' => 'Tiket valid! Selamat datang.',
                'ticket'  => $ticket,
            ]);
        } catch (Throwable $e) {
            return back()->withErrors(['qr_token' => $e->getMessage()]);
        }
    }
}
