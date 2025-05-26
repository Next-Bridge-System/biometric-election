<?php

namespace App\Http\Livewire\Admin\Members;

use App\Bar;
use App\District;
use App\Division;
use App\Member;
use App\MemberGroup;
use App\Tehsil;
use Livewire\Component;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search_member_name, $search_division_id, $search_bar_id, $search_group_id;
    public $divisions, $districts, $tehsils, $bars, $groups;
    public $selected_members_list = [], $selected_members = [];

    public function mount()
    {
        $this->divisions = Division::orderBy('name', 'asc')->get();
        $this->bars = Bar::orderBy('name', 'asc')->get();
        $this->groups = MemberGroup::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $query = Member::query();

        if ($this->search_member_name) {
            $query->where('name', 'like', '%' . $this->search_member_name . '%');
        }

        if ($this->search_division_id) {
            $query->where('division_id',  $this->search_division_id);
        }

        if ($this->search_bar_id) {
            $query->where('bar_id',  $this->search_bar_id);
        }

        if ($this->search_group_id) {
            $query->where('member_group_id',  $this->search_group_id);
        }

        $records = $query->orderBy('id', 'desc')->paginate(10);


        return view('livewire.admin.members.index-component', [
            'records' => $records
        ]);
    }

    public function addToGroupCart($id)
    {
        $this->selected_members[] = $id;
        $member = Member::find($id);

        $this->selected_members_list[] = [
            'id' => $member->id,
            'name' => $member->name,
            'division' => getDivisionName($member->division_id),
        ];
    }

    public function resetGroup()
    {
        $this->selected_members = [];
        $this->selected_members_list = [];
    }

    public function saveGroup()
    {
        $member_group = MemberGroup::create([
            'name' => 'Group'
        ]);

        $member_group->update(['name' => 'Group-' . $member_group->id]);

        if (count($this->selected_members_list) == 2) {
            foreach ($this->selected_members_list as $key => $cart_item) {
                $member = Member::find($cart_item['id']);
                $member->update(['member_group_id' => $member_group->id]);
            }
        }

        $this->resetGroup();
    }
}
