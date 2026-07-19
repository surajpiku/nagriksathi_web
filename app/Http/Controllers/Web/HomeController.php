<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = SchemeCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $featuredSchemes = Scheme::with('category')
            ->where('is_active', true)
            ->orderBy('benefit_value', 'desc')
            ->limit(6)
            ->get();

        return view('pages.home', compact('categories', 'featuredSchemes'));
    }

   
public function schemes(Request $request)
{
    $categories = \App\Models\SchemeCategory::where('is_active', true)
        ->orderBy('display_order')->get();

    $query = \App\Models\Scheme::where('is_active', true)->with('category');

    if ($request->category) {
        $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
    }

    $schemes = $query->orderBy('benefit_value', 'desc')->paginate(12)->withQueryString();

    return view('pages.schemes', compact('schemes', 'categories'));
}
public function schemeDetail($id)
{
    $scheme = Scheme::with(['category', 'steps'])->findOrFail($id);
    return view('pages.scheme-detail', compact('scheme'));
}
public function dashboard()
{
    return view('pages.dashboard');
}

public function profileSetup()
{
    return view('pages.profile-setup');
}

public function sathi()
{
    return view('pages.sathi');
}

public function search()
{
    $categories = SchemeCategory::where('is_active', true)->get();
    return view('pages.search', compact('categories'));
}

public function documents()
{
    return view('pages.documents');
}

public function applications()
{
    return view('pages.applications');
}

public function lifeEvents()
{
    return view('pages.life-events');
}
public function nagrikScore()
{
    return view('pages.nagrik-score');
}

public function calculator()
{
    $categories = SchemeCategory::where('is_active', true)->get();
    return view('pages.calculator', compact('categories'));
}
public function awasar()
{
    $categories = \App\Models\OpportunityCategory::where('is_active', true)
        ->orderBy('display_order')->get();

    $query = \App\Models\Opportunity::with('category')->where('is_active', true);

    // Level filter
if (request('level') === 'central') {
    $query->where('level', 'central')->whereNull('district')->whereNull('local_level');
} elseif (request('level') === 'state') {
    $query->where('level', 'state')->whereNull('district')->whereNull('local_level');
    if (request('state')) {
        $query->where('state_code', request('state'));
    }
} elseif (request('level') === 'district') {
    $query->whereNotNull('district');
    if (request('state')) {
        $query->where('state_code', request('state'));
    }
} elseif (request('level') === 'local') {
    $query->whereNotNull('local_level');
    if (request('local_level')) {
        $query->where('local_level', request('local_level'));
    }
    if (request('state')) {
        $query->where('state_code', request('state'));
    }
}

    // Category filter
    if (request('category')) {
        $query->whereHas('category', fn($q) => $q->where('slug', request('category')));
    }

    $featured   = (clone $query)->where('is_featured', true)->latest()->limit(6)->get();
    $deadlines  = \App\Models\Opportunity::with('category')
        ->where('is_active', true)
        ->whereNotNull('apply_end')
        ->where('apply_end', '>=', now())
        ->orderBy('apply_end')
        ->limit(5)->get();

    $allOpportunities = $query->latest()->get();

    return view('pages.awasar', compact('categories', 'featured', 'deadlines', 'allOpportunities'));
}
public function awasarDetail($id)
{
    $opportunity = \App\Models\Opportunity::with(['category', 'steps'])->findOrFail($id);
    return view('pages.awasar-detail', compact('opportunity'));
}

public function joinAsAgent()
{
    $states = \App\Models\StateMaster::where('is_active', true)->orderBy('name')->get();
    return view('pages.join-as-agent', compact('states'));
}
public function cscDashboard()
{
    return view('pages.csc.dashboard');
}

public function cscToolkit()
{
    return view('pages.csc.toolkit');
}

public function cscPortalStatus()
{
    return view('pages.csc.portal-status');
}

public function subscription()
{
    return view('pages.subscription');
}
public function hunar()
{
    $categories = \App\Models\ServiceCategory::where('is_active', true)
        ->orderBy('display_order')
        ->get();
    return view('pages.hunar', compact('categories'));
}
public function findSevaMitra()
{
    $states = \App\Models\StateMaster::where('is_active', true)->orderBy('name')->get();
    return view('pages.find-seva-mitra', compact('states'));
}
public function sevaMitraDetail($id)
{
    $agent = \App\Models\CscAgent::with('user')->findOrFail($id);
    return view('pages.seva-mitra-detail', compact('agent'));
}
}
