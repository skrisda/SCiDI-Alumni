<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipApplicationRequest;
use App\Http\Requests\StoreMembershipSlipRequest;
use App\Models\MembershipApplication;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MembershipApplicationController extends Controller
{
    public function show(Request $request): View
    {
        $nameParts = preg_split('/\s+/u', trim($request->user()->name), 2);

        return view('membership-application', [
            'application' => MembershipApplication::where('user_id', $request->user()->id)->first(),
            'defaultFirstName' => $nameParts[0] ?? '',
            'defaultLastName' => $nameParts[1] ?? '',
        ]);
    }

    public function store(StoreMembershipApplicationRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['acknowledged', 'prefix_other']);
        if ($data['prefix'] === 'other') {
            $data['prefix'] = $request->validated('prefix_other');
        }
        $data['user_id'] = $request->user()->id;
        $data['email'] = $request->user()->email;
        $data['national_id_hash'] = hash_hmac('sha256', $data['national_id'], config('app.key'));
        $data['fee_amount'] = match ($data['membership_type']) {
            'ordinary' => 100,
            'extraordinary' => 300,
            default => null,
        };
        $data['status'] = 'pending_review';
        $data['payment_status'] = $data['fee_amount'] === null ? 'awaiting_fee_confirmation' : 'awaiting_payment';
        try {
            MembershipApplication::create($data);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['national_id' => 'บัญชีหรือเลขประจำตัวประชาชนนี้มีคำขอสมัครแล้ว กรุณาตรวจสอบคำขอของคุณ']);
        }

        return to_route('membership.application')->with('status', 'ส่งคำขอสมัครเรียบร้อยแล้ว กรุณารอการตรวจสอบและพิจารณาจากสมาคม');
    }

    public function uploadSlip(StoreMembershipSlipRequest $request): RedirectResponse
    {
        $application = MembershipApplication::where('user_id', $request->user()->id)->firstOrFail();
        abort_unless($application->fee_amount !== null && $application->payment_status === 'awaiting_payment', 403);
        $path = $request->file('slip')->store('membership-slips/'.$request->user()->id, 'local');
        abort_if($path === false, 500, 'ไม่สามารถจัดเก็บไฟล์ได้ กรุณาลองใหม่');
        try {
            DB::transaction(function () use ($application, $path): void {
                $lockedApplication = MembershipApplication::whereKey($application->id)->lockForUpdate()->firstOrFail();
                abort_unless($lockedApplication->payment_status === 'awaiting_payment', 403);
                $lockedApplication->update(['slip_path' => $path, 'payment_status' => 'pending_verification']);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return to_route('membership.application')->with('status', 'ได้รับหลักฐานแล้ว รอเจ้าหน้าที่ตรวจสอบ ยังไม่ถือว่าได้รับอนุมัติเป็นสมาชิก');
    }

    public function downloadSlip(Request $request): StreamedResponse
    {
        $application = MembershipApplication::where('user_id', $request->user()->id)->firstOrFail();
        abort_unless($application->slip_path && Storage::disk('local')->exists($application->slip_path), 404);

        return Storage::disk('local')->download($application->slip_path);
    }
}
