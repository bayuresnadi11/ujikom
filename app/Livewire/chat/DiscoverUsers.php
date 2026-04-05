<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DiscoverUsers extends Component
{
    public $userType = 'all'; // all, buyer, landowner, admin
    public $search = '';

    protected $listeners = ['searchUpdated' => 'updateSearch'];

    public function mount($search = '')
    {
        $this->search = $search;
    }

    #[On('searchUpdated')]
    public function updateSearch($search)
    {
        $this->search = $search;
    }

    public function updatedUserType()
    {
        // Trigger re-render when user type changes
        $this->dispatch('userTypeChanged', $this->userType);
    }

    public function render()
    {
        $users = collect();

        // Only search if there is input (minimum 2 character)
        if (mb_strlen(trim($this->search)) >= 2) {

            $query = User::query();
            
            // Exclude current user
            $query->where('id', '!=', Auth::id());
            
            // Search by name OR phone
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm)
                  ->orWhere('phone', 'LIKE', $searchTerm);
            });

            // ALWAYS exclude cashier role
            $query->where('role', '!=', 'cashier');

            // Filter by user type if not 'all'
            if ($this->userType !== 'all') {
                $query->where('role', $this->userType);
            }
            
            // Order by name
            $query->orderBy('name', 'asc');
            
            // Limit results
            $users = $query->limit(50)->get();
        }

        return view('livewire.chat.discover-users', [
            'users' => $users
        ]);
    }

    public function startChat($userId)
    {
        return redirect()->route('chat.create', $userId);
    }
}