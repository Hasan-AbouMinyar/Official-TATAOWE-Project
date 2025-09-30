<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search across all entities (users, organizations, events)
     */
    public function searchAll(Request $request)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 10);

        if (empty($query)) {
            return response()->json([
                'users' => [],
                'organizations' => [],
                'events' => [],
                'total' => 0
            ]);
        }

        // Split query into words for better matching
        $searchTerms = explode(' ', $query);

        // Search users - enhanced with phoneNumber
        $users = User::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('username', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phoneNumber', 'LIKE', "%{$query}%");
            
            // Search each word separately for better matches
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('username', 'LIKE', "%{$term}%");
                }
            }
        })
        ->limit($limit)
        ->get(['id', 'name', 'username', 'email', 'photo', 'phoneNumber']);

        // Search organizations - enhanced with field, email, phone, address
        $organizations = Organization::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('field', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('address', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
            
            // Search each word separately
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->orWhere('field', 'LIKE', "%{$term}%");
                }
            }
        })
        ->with('user:id,name,photo')
        ->withCount('events')
        ->limit($limit)
        ->get();

        // Search events - enhanced with requiredSkills
        $events = Event::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%")
              ->orWhere('requiredSkills', 'LIKE', "%{$query}%");
            
            // Search each word separately
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->orWhere('location', 'LIKE', "%{$term}%");
                }
            }
        })
        ->with([
            'organization:id,name,photo',
            'organization.user:id,name,photo'
        ])
        ->withCount('applications')
        ->limit($limit)
        ->get();

        return response()->json([
            'users' => $users,
            'organizations' => $organizations,
            'events' => $events,
            'total' => $users->count() + $organizations->count() + $events->count()
        ]);
    }

    /**
     * Search users only
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);

        if (empty($query)) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0
            ]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate($perPage, ['id', 'name', 'username', 'email', 'photo', 'created_at']);

        return response()->json([
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total(),
            'per_page' => $users->perPage()
        ]);
    }

    /**
     * Search organizations only
     */
    public function searchOrganizations(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);

        if (empty($query)) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0
            ]);
        }

        $searchTerms = explode(' ', $query);

        $organizations = Organization::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('field', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('address', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->orWhere('field', 'LIKE', "%{$term}%");
                }
            }
        })
        ->with('user:id,name,photo')
        ->withCount('events')
        ->paginate($perPage);

        return response()->json([
            'data' => $organizations->items(),
            'current_page' => $organizations->currentPage(),
            'last_page' => $organizations->lastPage(),
            'total' => $organizations->total(),
            'per_page' => $organizations->perPage()
        ]);
    }

    /**
     * Search events only
     */
    public function searchEvents(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);

        if (empty($query)) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0
            ]);
        }

        $searchTerms = explode(' ', $query);

        $events = Event::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%")
              ->orWhere('requiredSkills', 'LIKE', "%{$query}%");
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->orWhere('location', 'LIKE', "%{$term}%");
                }
            }
        })
        ->with([
            'organization:id,name,photo',
            'organization.user:id,name,photo'
        ])
        ->withCount('applications')
        ->paginate($perPage);

        return response()->json([
            'data' => $events->items(),
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
            'total' => $events->total(),
            'per_page' => $events->perPage()
        ]);
    }

    /**
     * Advanced search with filters
     */
    public function advancedSearch(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all'); // all, users, organizations, events
        $filters = $request->input('filters', []);
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);

        $results = [];

        switch ($type) {
            case 'users':
                $results = $this->searchUsersAdvanced($query, $filters, $perPage);
                break;
            case 'organizations':
                $results = $this->searchOrganizationsAdvanced($query, $filters, $perPage);
                break;
            case 'events':
                $results = $this->searchEventsAdvanced($query, $filters, $perPage);
                break;
            default:
                $results = [
                    'users' => $this->searchUsersAdvanced($query, $filters, 5),
                    'organizations' => $this->searchOrganizationsAdvanced($query, $filters, 5),
                    'events' => $this->searchEventsAdvanced($query, $filters, 5),
                ];
        }

        return response()->json($results);
    }

    private function searchUsersAdvanced($query, $filters, $perPage)
    {
        $builder = User::query();

        if (!empty($query)) {
            $builder->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            });
        }

        // Apply filters
        if (isset($filters['has_organizations']) && $filters['has_organizations']) {
            $builder->has('organizations');
        }

        if (isset($filters['has_applications']) && $filters['has_applications']) {
            $builder->has('applications');
        }

        $users = $builder->paginate($perPage, ['id', 'name', 'username', 'email', 'photo', 'created_at']);

        return [
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total()
        ];
    }

    private function searchOrganizationsAdvanced($query, $filters, $perPage)
    {
        $builder = Organization::query();

        if (!empty($query)) {
            $searchTerms = explode(' ', $query);
            $builder->where(function($q) use ($query, $searchTerms) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('field', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('address', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%");
                
                foreach ($searchTerms as $term) {
                    if (strlen($term) > 2) {
                        $q->orWhere('name', 'LIKE', "%{$term}%")
                          ->orWhere('description', 'LIKE', "%{$term}%")
                          ->orWhere('field', 'LIKE', "%{$term}%");
                    }
                }
            });
        }

        // Apply filters
        if (isset($filters['has_events']) && $filters['has_events']) {
            $builder->has('events');
        }

        if (isset($filters['field'])) {
            $builder->where('field', 'LIKE', "%{$filters['field']}%");
        }

        $organizations = $builder->with('user:id,name,photo')
            ->withCount('events')
            ->paginate($perPage);

        return [
            'data' => $organizations->items(),
            'current_page' => $organizations->currentPage(),
            'last_page' => $organizations->lastPage(),
            'total' => $organizations->total()
        ];
    }

    private function searchEventsAdvanced($query, $filters, $perPage)
    {
        $builder = Event::query();

        if (!empty($query)) {
            $searchTerms = explode(' ', $query);
            $builder->where(function($q) use ($query, $searchTerms) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%")
                  ->orWhere('requiredSkills', 'LIKE', "%{$query}%");
                
                foreach ($searchTerms as $term) {
                    if (strlen($term) > 2) {
                        $q->orWhere('name', 'LIKE', "%{$term}%")
                          ->orWhere('description', 'LIKE', "%{$term}%")
                          ->orWhere('location', 'LIKE', "%{$term}%");
                    }
                }
            });
        }

        // Apply filters
        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $builder->where('start_time', '>', now());
        }

        if (isset($filters['past']) && $filters['past']) {
            $builder->where('end_time', '<', now());
        }

        if (isset($filters['location'])) {
            $builder->where('location', 'LIKE', "%{$filters['location']}%");
        }

        if (isset($filters['organization_id'])) {
            $builder->where('organization_id', $filters['organization_id']);
        }

        if (isset($filters['has_spaces']) && $filters['has_spaces']) {
            $builder->whereColumn('applications_count', '<', 'max_volunteers');
        }

        $events = $builder->with([
                'organization:id,name,photo',
                'organization.user:id,name,photo'
            ])
            ->withCount('applications')
            ->paginate($perPage);

        return [
            'data' => $events->items(),
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
            'total' => $events->total()
        ];
    }

    /**
     * Get search suggestions (autocomplete)
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 5);

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $searchTerms = explode(' ', $query);
        $suggestions = [];

        // User suggestions
        $users = User::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('username', 'LIKE', "%{$query}%");
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 1) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('username', 'LIKE', "%{$term}%");
                }
            }
        })
        ->limit($limit)
        ->get(['id', 'name', 'username', 'photo'])
        ->map(function($user) {
            return [
                'type' => 'user',
                'id' => $user->id,
                'text' => $user->name,
                'subtext' => '@' . $user->username,
                'photo' => $user->photo,
                'url' => '/users/' . $user->id
            ];
        });

        // Organization suggestions
        $organizations = Organization::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('field', 'LIKE', "%{$query}%");
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 1) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('field', 'LIKE', "%{$term}%");
                }
            }
        })
        ->limit($limit)
        ->get(['id', 'name', 'photo', 'field'])
        ->map(function($org) {
            return [
                'type' => 'organization',
                'id' => $org->id,
                'text' => $org->name,
                'subtext' => $org->field ?? 'Organization',
                'photo' => $org->photo,
                'url' => '/organizations/' . $org->id
            ];
        });

        // Event suggestions
        $events = Event::where(function($q) use ($query, $searchTerms) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 1) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('location', 'LIKE', "%{$term}%");
                }
            }
        })
        ->limit($limit)
        ->get(['id', 'name', 'photo', 'start_time', 'location'])
        ->map(function($event) {
            return [
                'type' => 'event',
                'id' => $event->id,
                'text' => $event->name,
                'subtext' => $event->location ?? $event->start_time->format('Y-m-d'),
                'photo' => $event->photo,
                'url' => '/events/' . $event->id
            ];
        });

        $suggestions = $users->concat($organizations)->concat($events);

        return response()->json($suggestions);
    }
}
