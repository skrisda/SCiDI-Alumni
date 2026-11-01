<?php

namespace App\Http\Controllers;

use App\Models\MembershipApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminMembershipController extends Controller
{
    public function dashboard(): View
    {
        return view('admin-dashboard', [
            'totalApplications' => MembershipApplication::count(),
            'pendingApplications' => MembershipApplication::where('status', 'pending_review')->count(),
            'pendingPayments' => MembershipApplication::where('payment_status', 'pending_verification')->count(),
        ]);
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::in(array_keys(MembershipApplication::TYPES))],
            'payment' => ['nullable', Rule::in(['awaiting_payment', 'pending_verification', 'awaiting_fee_confirmation'])],
        ], ['max' => 'คำค้นยาวเกินกำหนด', 'in' => 'ตัวกรองไม่ถูกต้อง']);
        $query = MembershipApplication::query();
        if (filled($filters['search'] ?? null)) {
            $search = '%'.$filters['search'].'%';
            $query->where(function (Builder $query) use ($search): void {
                $query->where('first_name', 'like', $search)->orWhere('last_name', 'like', $search);
            });
        }
        if (filled($filters['type'] ?? null)) {
            $query->where('membership_type', $filters['type']);
        }
        if (filled($filters['payment'] ?? null)) {
            $query->where('payment_status', $filters['payment']);
        }

        return view('admin-memberships', ['applications' => $query->select(['id', 'prefix', 'first_name', 'last_name', 'membership_type', 'status', 'payment_status', 'created_at'])->latest('id')->paginate(15)->withQueryString()]);
    }

    public function show(MembershipApplication $application): View
    {
        Log::info('Admin viewed membership application', ['actor_id' => auth()->id(), 'application_id' => $application->id]);

        return view('admin-membership', ['application' => $application]);
    }

    public function slip(MembershipApplication $application): StreamedResponse
    {
        Gate::authorize('view-membership-payments');
        abort_unless($application->slip_path && Storage::disk('local')->exists($application->slip_path), 404);
        Log::info('Admin downloaded membership slip', ['actor_id' => auth()->id(), 'application_id' => $application->id]);

        return Storage::disk('local')->download($application->slip_path);
    }
}
