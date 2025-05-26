<?php

namespace App\Http\Livewire\Admin\Post;

use App\Note;
use App\Post;
use App\PostType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class IndexComponent extends Component
{
    public $filter, $search = false;
    public $edit_mode = false;

    public $post_id;
    public $lawyer_name, $father_husband, $cnic_no, $mobile_no, $address, $picture;
    public $capturedImage, $webcam_image_url;

    public $selected_row_id, $rcpt_no, $rcpt_date;
    public $post_note, $post_notes_list = [];

    public $subject, $post_type;
    public $post_types = [];

    public $search_key = 'name', $search_value = "";
    public $search_date_type = 'created_at', $search_date_key = "", $search_date_value_1 = "", $search_date_value_2 = "";

    protected $listeners = [
        'set-captured-image' => 'getImageFromCam',
        'set-post-notes' => 'getPostNotes',
    ];

    public function getImageFromCam($image)
    {
        $this->capturedImage = $image;
    }

    public function render()
    {
        $records = [];

        $filter = [
            'search_key' => $this->search_key,
            'search_value' => $this->search_value,
            'search_date_type' => $this->search_date_type,
            'search_date_key' => $this->search_date_key,
            'search_date_value_1' => $this->search_date_value_1,
            'search_date_value_2' => $this->search_date_value_2,
        ];

        $query = Post::with('postNotes', 'postTypeRelation');

        $query->when($filter['search_key'] == 'name' && $filter['search_value'], function ($qry) use ($filter) {
            return $qry->where('lawyer_name', 'LIKE', '%' . $filter['search_value'] . '%');
        });

        $query->when($filter['search_key'] == 'father' && $filter['search_value'], function ($qry) use ($filter) {
            return $qry->where('father_husband', 'LIKE', '%' . $filter['search_value'] . '%');
        });

        $query->when($filter['search_key'] == 'cnic' && $filter['search_value'], function ($qry) use ($filter) {
            return $qry->where('cnic_no', 'LIKE', '%' . $filter['search_value'] . '%');
        });

        $query->when($filter['search_date_key'], function ($qry) use ($filter) {
            switch ($filter['search_date_key']) {
                case 'today':
                    $qry->whereDate($filter['search_date_type'], Carbon::today());
                    break;

                case 'yesterday':
                    $qry->whereDate($filter['search_date_type'], Carbon::yesterday());
                    break;

                case 'this_week':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;

                case 'last_week':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                    break;

                case 'this_month':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;

                case 'last_month':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
                    break;

                case 'this_year':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                    break;

                case 'last_year':
                    $qry->whereBetween($filter['search_date_type'], [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()]);
                    break;

                case 'date_range':
                    $qry->whereDate($filter['search_date_type'], '>=', $filter['search_date_value_1']);
                    $qry->whereDate($filter['search_date_type'], '<=', $filter['search_date_value_2']);
                    break;
            }
        });

        $records = $query->orderBy('id', 'desc')->paginate(25);

        return view('livewire.admin.post.index-component', [
            'records' => $records,
        ]);
    }

    public function save()
    {
        $this->validate([
            'subject' => ['required', 'string', 'max:255'],
            'post_type' => ['required', 'integer'],

            'lawyer_name' => ['required', 'string', 'max:255'],
            'father_husband' => ['nullable'],
            'address' => ['nullable'],
            'cnic_no' => ['nullable', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            'mobile_no' => ['nullable', 'regex:/^\d{3}-\d{7}$/'],
        ]);

        $data = [
            'lawyer_name' => $this->lawyer_name,
            'father_husband' => $this->father_husband,
            'address' => $this->address,
            'cnic_no' => $this->cnic_no,
            'mobile_no' => $this->mobile_no,
            'clean_cnic_no' => cleanData($this->cnic_no),
            'clean_mobile_no' => cleanData($this->mobile_no),

            'subject' => $this->subject,
            'post_type' => $this->post_type,
        ];

        if ($this->edit_mode == true) {
            $post = Post::find($this->post_id);
            $post->update($data);
        } else {
            $post = Post::create($data);
        }

        if ($this->capturedImage) {
            $image_data = explode(',', $this->capturedImage)[1];
            $image_name = 'post_' . $post->id . '_' . time() . '.png';
            Storage::disk('webcam')->put($image_name, base64_decode($image_data));
            $this->webcam_image_url = 'webcam/' . $image_name;
            $post->update(['webcam_image_url' => $this->webcam_image_url]);
        }

        if ($this->edit_mode) {
            $this->alert('Post Updated!', 'The post have been updated successfully.');
        } else {
            $this->alert('Post Created!', 'The post have been created successfully.');
        }

        $this->clear();
    }

    public function create()
    {
        $this->edit_mode = false;
        $this->resetInputFields();
        $this->post_types = PostType::orderBy('id', 'asc')->get();
    }

    public function store()
    {
        $this->save();
        $this->emit('hide_modal');
    }

    public function edit($id)
    {
        $this->edit_mode = true;
        $post = Post::where('id', $id)->first();
        $this->post_id = $post->id;
        $this->lawyer_name = $post->lawyer_name;
        $this->father_husband = $post->father_husband;
        $this->address = $post->address;
        $this->cnic_no = $post->cnic_no;
        $this->mobile_no = $post->mobile_no;
        $this->webcam_image_url = $post->webcam_image_url;

        $this->subject = $post->subject;
        $this->post_type = $post->post_type;

        $this->post_types = PostType::orderBy('id', 'asc')->get();

        $this->dispatchBrowserEvent('show_modal', ['modalId' => "post_modal"]);
    }

    public function update()
    {
        $this->save();
        $this->emit('close_modal');
    }

    public function saveImage()
    {
        // RENDERING ...
    }

    public function alert($title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => $title,
            'text' =>  $text
        ]);
    }

    public function openRcpt($id)
    {
        $this->selected_row_id = $id;
    }

    public function updateRcpt($id)
    {
        $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $rcpt = Post::select('rcpt_no', 'rcpt_date')->orderBy('rcpt_no', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

        if ($rcpt == null) {
            $rcpt_count = 1;
        } else {
            $rcpt_count = $rcpt->rcpt_no  + 1;
        }

        $intimation = Post::updateOrCreate(['id' =>  $id], [
            'rcpt_no' => sprintf("%02d", $rcpt_count),
            'rcpt_date' => $rcpt_date,
        ]);

        $this->rcpt_no = $intimation->rcpt_no;
        $this->rcpt_date = getDateFormat($intimation->rcpt_date);
    }

    public function openNotes($id)
    {
        $this->selected_row_id = $id;
        $this->post_notes_list = Note::where('application_id', $id)->where('application_type', 'POST')->get();
    }

    public function getPostNotes($contents)
    {
        $this->post_note = $contents;
    }

    public function saveNotes()
    {
        $rules = [
            'post_note' => 'required',
        ];

        $data = [
            'post_note' => $this->post_note,
        ];

        $validator = Validator::make($data, $rules);

        Note::create([
            'application_id' => $this->selected_row_id,
            'application_type' => 'POST',
            'note' => $this->post_note,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        $this->alert('Note Added!', 'The notes have been added successfully.');
        $this->emit('hide_modal');
    }

    public function resetInputFields()
    {
        $this->selected_row_id = null;
        $this->rcpt_no = null;
        $this->rcpt_date = null;
        $this->post_note = null;

        $this->lawyer_name = $this->father_husband = $this->cnic_no = $this->mobile_no = $this->address = $this->picture = $this->webcam_image_url = NULL;
        $this->subject = $this->post_type = NULL;

        $this->resetValidation();
    }

    public function close()
    {
        $this->resetInputFields();
    }

    public function search()
    {
        $this->search = true;
    }

    public function clear()
    {
        $this->search_value = "";
        $this->search_date_type = "created_at";
        $this->search_date_key = "";
        $this->search_date_value_1 = "";
        $this->search_date_value_2 = "";
        $this->search = false;
        $this->resetInputFields();
    }
}
