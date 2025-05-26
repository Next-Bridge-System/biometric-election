<?php

namespace App\Http\Livewire\Frontend\LawyerRequests;

use App\LawyerRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IndexComponent extends Component
{

    public function render()
    {
        $records = LawyerRequest::where('user_id', Auth::guard('frontend')->id())->paginate(10);
        return view('livewire.frontend.lawyer-requests.index-component', [
            'records' => $records
        ]);
    }
}
