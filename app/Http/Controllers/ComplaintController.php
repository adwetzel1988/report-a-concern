<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Officer;
use App\Models\State;
use App\Models\User;
use App\Notifications\NewComplaintSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function create()
    {
        $states = State::with('cities')->get();
        return view('complaints.create', compact('states'));
    }

    public function index()
    {
        $complaints = auth()->user()->complaints()->get();
        return view('complaints.index', compact('complaints'));
    }

    public function store(Request $request)
    {
        $userId = null;

        $validatedData = $request->validate([
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'complaint_type' => 'nullable|string|max:255',
            'custom_type' => 'nullable|string|max:255',
            'officer_name' => 'nullable|string|max:255',
            'officer_rank' => 'nullable|string|max:255',
            'officer_division' => 'nullable|string|max:255',
            'officer_badge_number' => 'nullable|string|max:255',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,pdf,doc,docx|max:10240',
            'city_address' => 'nullable|string|max:255',
            'person_name' => 'nullable|string|max:255',
            'person_number' => 'nullable|string|max:15',
            'person_email' => 'nullable|email',
        ]);

        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $validatedData = array_merge($validatedData, $request->validate([
                'anonymous' => 'required|boolean',
                'first_name' => 'required_if:anonymous,1|nullable|string|max:255',
                'last_name' => 'required_if:anonymous,1|nullable|string|max:255',
                'phone' => 'required_if:anonymous,1|nullable|string|max:15',
                'email' => 'required_if:anonymous,1|nullable|email|unique:users,email',
                'password' => 'required_if:anonymous,1|nullable|string|min:8',
                'address' => 'required_if:anonymous,1|nullable|string|max:255',
                'person_city' => 'required_if:anonymous,1|nullable|string|max:255',
                'person_state' => 'required_if:anonymous,1|nullable|string|max:255',
                'zip' => 'required_if:anonymous,1|nullable|string|max:10',
            ]));

            if ($validatedData['anonymous'] == '1') {
                $user = User::create([
                    'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'phone' => $validatedData['phone'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['person_city'],
                    'state' => $validatedData['person_state'],
                    'zip' => $validatedData['zip'],
                ]);

                Auth::login($user);
                $userId = $user->id;
            }
        }

        $complaint = Complaint::create([
            'user_id' => $userId,
            'complaint_number' => 'C-' . Str::random(8),
            'description' => $validatedData['description'],
            'incident_date' => $validatedData['incident_date'],
            'complaint_type' => $validatedData['complaint_type'],
            'status' => 'pending',
            'address' => $validatedData['city_address'],
            'person_name' => $validatedData['person_name'],
            'person_number' => $validatedData['person_number'],
            'person_email' => $validatedData['person_email'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('public');
                $complaint->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $complaint->id . '-' . $attachment->getClientOriginalName(),
                ]);
            }
        }

        if (isset($validatedData['witnesses'])) {
            foreach ($validatedData['witnesses'] as $witness) {
                $complaint->witnesses()->create($witness);
            }
        }

        $this->notify($complaint);

        return redirect()->route('complaints.thank-you', ['complaint' => $complaint]);
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['attachments', 'officer', 'witnesses', 'notes']);
        return view('complaints.show', compact('complaint'));
    }

    public function thankYou(Complaint $complaint)
    {
        return view('complaints.thank-you', ['complaint' => $complaint]);
    }

    public function searchForm()
    {
        return view('complaints.search');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Complaint::where(function ($q) use ($query) {
            $q->where('complaint_number', 'LIKE', "%$query%")
                ->orWhere('address', 'LIKE', "%$query%")
                ->orWhere('person_name', 'LIKE', "%$query%")
                ->orWhere('person_number', 'LIKE', "%$query%")
                ->orWhere('person_email', 'LIKE', "%$query%");
        })
            ->get();

        return view('complaints.results', [
            'results' => $results,
            'query' => $query
        ]);
    }

    private function notify(Complaint $complaint): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewComplaintSubmitted($complaint));
        }
    }
}
