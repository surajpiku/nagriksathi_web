<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\UserService;
use App\Models\ServiceReview;
use App\Models\ServiceContact;
use App\Models\ServicePrivacy;
use Illuminate\Http\Request;

class HunarController extends Controller
{
    // GET /hunar/categories — PUBLIC
    public function categories()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->withCount('services')
            ->orderBy('display_order')
            ->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    // GET /hunar/categories/{id}/types — PUBLIC
    public function types($id)
    {
        $types = ServiceType::where('category_id', $id)
            ->where('is_active', true)
            ->get();

        return response()->json(['success' => true, 'data' => $types]);
    }

    // GET /hunar/search — PUBLIC
    public function search(Request $request)
    {
        $user    = $request->user(); // nullable — public route
        $profile = $user?->profile;
        $isGuest = !$user;

        $query = UserService::with(['user.profile', 'category', 'type', 'user.privacy'])
            ->where('status', 'active');

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by service type
        if ($request->type_id) {
            $query->where('service_type_id', $request->type_id);
        }

        // Filter by availability
        if ($request->availability) {
            $query->where('availability', $request->availability);
        }

        // Filter by price range
        if ($request->price_range) {
            $query->where('price_range', $request->price_range);
        }

        // Text search
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->whereHas('type', fn($sq) =>
                    $sq->where('name', 'like', '%'.$request->q.'%')
                       ->orWhere('hindi_name', 'like', '%'.$request->q.'%')
                )->orWhere('description', 'like', '%'.$request->q.'%');
            });
        }

        // Smart location filter — most specific first
        if ($request->pincode) {
            $query->whereHas('user.profile', fn($q) =>
                $q->where('pincode', $request->pincode)
            );
        } elseif ($request->block) {
            $query->whereHas('user.profile', fn($q) =>
                $q->where('block', 'like', '%'.$request->block.'%')
            );
        } elseif ($request->district) {
            $query->whereHas('user.profile', fn($q) =>
                $q->where('district', 'like', '%'.$request->district.'%')
            );
        } elseif ($request->state) {
            $query->whereHas('user.profile', fn($q) =>
                $q->where('state', 'like', '%'.$request->state.'%')
            );
        } elseif ($profile && $profile->district) {
            // Default — use logged in user's district
            $query->whereHas('user.profile', fn($q) =>
                $q->where('district', $profile->district)
                  ->orWhere('state', $profile->state)
            );
        }

        // Privacy filter — hide hidden profiles
        $query->whereDoesntHave('user.privacy', fn($q) =>
            $q->where('visibility', 'hidden')
        );

        $services = $query->orderBy('rating', 'desc')
            ->orderBy('contact_count', 'desc')
            ->paginate(20);

        $data = $services->map(function($service) use ($isGuest) {
            $privacy = $service->user->privacy;
            $profile = $service->user->profile;
            $phone   = $service->user->phone;

            // Mask phone for guests
            $maskedPhone = null;
            $fullPhone   = null;
            if ($phone) {
                $maskedPhone = substr($phone, 0, 5) . 'XXXXX'; // 98765XXXXX
                $fullPhone   = (!$isGuest && $privacy?->show_phone) ? $phone : null;
            }

            return [
                'id'              => $service->id,
                'category'        => $service->category->name,
                'category_icon'   => $service->category->icon,
                'service_type'    => $service->type->name,
                'service_type_hindi' => $service->type->hindi_name,
                'description'     => $service->description,
                'availability'    => $service->availability,
                'price_range'     => $service->price_range,
                'service_area'    => $service->service_area,
                'rating'          => $service->rating,
                'review_count'    => $service->review_count,
                'contact_count'   => $service->contact_count,
                'is_verified'     => $service->is_verified,
                'provider'        => [
                    'id'           => $service->user->id,
                    'name'         => $profile?->name ?? 'Provider',
                    'gender'       => $profile?->gender,
                    'location'     => $this->getLocation($profile, $privacy),
                    'village'      => $profile?->village,
                    'block'        => $profile?->block,
                    'district'     => $profile?->district,
                    'state'        => $profile?->state,
                    'pincode'      => $profile?->pincode,
                    'phone'        => $fullPhone,          // full phone — logged in + show_phone=true
                    'masked_phone' => $maskedPhone,        // partial — for guests
                    'has_phone'    => !empty($phone),      // whether phone exists
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data,
            'total'   => $services->total(),
            'is_guest'=> $isGuest,
        ]);
    }

    // GET /hunar/providers/{id} — PUBLIC
    public function providerDetail($id)
    {
        $service = UserService::with(['user.profile', 'category', 'type', 'reviews.reviewer.profile'])
            ->findOrFail($id);

        $privacy = $service->user->privacy;
        $profile = $service->user->profile;
        $phone   = $service->user->phone;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'           => $service->id,
                'category'     => $service->category->name,
                'category_icon'=> $service->category->icon,
                'service_type' => $service->type->name,
                'description'  => $service->description,
                'availability' => $service->availability,
                'price_range'  => $service->price_range,
                'service_area' => $service->service_area,
                'rating'       => $service->rating,
                'review_count' => $service->review_count,
                'is_verified'  => $service->is_verified,
                'provider'     => [
                    'id'           => $service->user->id,
                    'name'         => $profile?->name ?? 'Provider',
                    'gender'       => $profile?->gender,
                    'location'     => $this->getLocation($profile, $privacy),
                    'district'     => $profile?->district,
                    'state'        => $profile?->state,
                    'phone'        => ($privacy?->show_phone) ? $phone : null,
                    'masked_phone' => $phone ? substr($phone, 0, 5) . 'XXXXX' : null,
                    'has_phone'    => !empty($phone),
                ],
                'reviews' => $service->reviews->where('is_approved', true)->take(10)->map(fn($r) => [
                    'rating'   => $r->rating,
                    'comment'  => $r->comment,
                    'reviewer' => $r->reviewer->profile?->name ?? 'User',
                    'date'     => $r->created_at->diffForHumans(),
                ]),
            ],
        ]);
    }

    // POST /hunar/providers/{id}/contact — AUTH
    public function logContact(Request $request, $id)
    {
        $request->validate(['contact_method' => 'required|in:in_app_chat,phone_call,whatsapp']);

        $service = UserService::findOrFail($id);

        ServiceContact::create([
            'user_service_id' => $service->id,
            'seeker_id'       => $request->user()->id,
            'contact_method'  => $request->contact_method,
            'contacted_at'    => now(),
        ]);

        $service->increment('contact_count');

        // Return full phone after contact logged
        $phone = $service->user->phone;
        return response()->json([
            'success' => true,
            'message' => 'Contact logged',
            'phone'   => $phone,
        ]);
    }

    // GET /hunar/my-services — AUTH
    public function myServices(Request $request)
    {
        $services = UserService::with(['category', 'type'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json(['success' => true, 'data' => $services]);
    }

    // POST /hunar/my-services — AUTH
    public function addService(Request $request)
    {
        $user    = $request->user();
        $profile = $user->profile;

        // Check profile has location
        if (!$profile || !$profile->district) {
            return response()->json([
                'success'  => false,
                'message'  => 'Please complete your profile with location first',
                'redirect' => '/profile/setup',
            ], 422);
        }

        $request->validate([
            'category_id'     => 'required|exists:service_categories,id',
            'service_type_id' => 'required|exists:service_types,id',
            'description'     => 'nullable|string|max:200',
            'availability'    => 'required|in:available_now,available_today,by_appointment,weekdays_only,weekends_only',
            'price_range'     => 'required|in:free,negotiable,low,medium,high',
            'service_area'    => 'nullable|string|max:150',
        ]);

        // Max 5 services per user
        $count = UserService::where('user_id', $user->id)->count();
        if ($count >= 5) {
            return response()->json(['success' => false, 'message' => 'Maximum 5 services allowed'], 422);
        }

        $service = UserService::create([
            'user_id'         => $user->id,
            'category_id'     => $request->category_id,
            'service_type_id' => $request->service_type_id,
            'description'     => $request->description,
            'availability'    => $request->availability,
            'price_range'     => $request->price_range,
            'service_area'    => $request->service_area, // extra detail only
            'languages_json'  => [],
            'status'          => 'active',
        ]);

        return response()->json(['success' => true, 'data' => $service->load(['category', 'type'])], 201);
    }

    // PUT /hunar/my-services/{id} — AUTH
    public function updateService(Request $request, $id)
    {
        $service = UserService::where('user_id', $request->user()->id)->findOrFail($id);
        $service->update($request->only(['description', 'availability', 'price_range', 'service_area']));
        return response()->json(['success' => true, 'data' => $service->load(['category', 'type'])]);
    }

    // DELETE /hunar/my-services/{id} — AUTH
    public function removeService(Request $request, $id)
    {
        UserService::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Service removed']);
    }

    // PUT /hunar/my-services/{id}/pause — AUTH
    public function pauseService(Request $request, $id)
    {
        UserService::where('user_id', $request->user()->id)->findOrFail($id)->update(['status' => 'paused']);
        return response()->json(['success' => true, 'message' => 'Service paused']);
    }

    // PUT /hunar/my-services/{id}/resume — AUTH
    public function resumeService(Request $request, $id)
    {
        UserService::where('user_id', $request->user()->id)->findOrFail($id)->update(['status' => 'active']);
        return response()->json(['success' => true, 'message' => 'Service resumed']);
    }

    // GET /hunar/privacy — AUTH
    public function getPrivacy(Request $request)
    {
        $privacy = ServicePrivacy::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['visibility' => 'everyone', 'location_precision' => 'block', 'contact_preference' => 'both']
        );
        return response()->json(['success' => true, 'data' => $privacy]);
    }

    // PUT /hunar/privacy — AUTH
    public function updatePrivacy(Request $request)
    {
        $privacy = ServicePrivacy::firstOrCreate(['user_id' => $request->user()->id]);
        $privacy->update($request->only(['visibility', 'location_precision', 'contact_preference', 'show_phone', 'all_paused']));
        return response()->json(['success' => true, 'data' => $privacy]);
    }

    // POST /hunar/reviews — AUTH
    public function addReview(Request $request)
    {
        $request->validate([
            'user_service_id' => 'required|exists:user_services,id',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:300',
        ]);

        $hasContacted = ServiceContact::where('user_service_id', $request->user_service_id)
            ->where('seeker_id', $request->user()->id)->exists();

        if (!$hasContacted) {
            return response()->json(['success' => false, 'message' => 'Contact provider before reviewing'], 422);
        }

        $existing = ServiceReview::where('user_service_id', $request->user_service_id)
            ->where('reviewer_id', $request->user()->id)->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Already reviewed'], 422);
        }

        $review  = ServiceReview::create([
            'user_service_id' => $request->user_service_id,
            'reviewer_id'     => $request->user()->id,
            'rating'          => $request->rating,
            'comment'         => $request->comment,
        ]);

        $service = UserService::find($request->user_service_id);
        $avg     = ServiceReview::where('user_service_id', $service->id)->avg('rating');
        $service->update([
            'rating'       => round($avg, 2),
            'review_count' => ServiceReview::where('user_service_id', $service->id)->count(),
        ]);

        return response()->json(['success' => true, 'data' => $review], 201);
    }

    // GET /hunar/providers/{id}/reviews — PUBLIC
    public function reviews($id)
    {
        $reviews = ServiceReview::with('reviewer.profile')
            ->where('user_service_id', $id)
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $reviews]);
    }

    // POST /hunar/report — AUTH
    public function report(Request $request)
    {
        $request->validate([
            'user_service_id' => 'required|exists:user_services,id',
            'reason'          => 'required|string|max:300',
        ]);

        \Log::warning('Hunar Report', [
            'reporter_id'     => $request->user()->id,
            'user_service_id' => $request->user_service_id,
            'reason'          => $request->reason,
        ]);

        return response()->json(['success' => true, 'message' => 'Report submitted. Admin will review within 24 hours.']);
    }

    // Helper — get location based on privacy setting
    private function getLocation($profile, $privacy): string
    {
        if (!$profile) return 'Unknown';

        $precision = $privacy?->location_precision ?? 'block';

        return match($precision) {
            'village'  => implode(', ', array_filter([$profile->village, $profile->block, $profile->district, $profile->state])),
            'block'    => implode(', ', array_filter([$profile->block, $profile->district, $profile->state])),
            'district' => implode(', ', array_filter([$profile->district, $profile->state])),
            default    => implode(', ', array_filter([$profile->block, $profile->district, $profile->state])),
        };
    }
}