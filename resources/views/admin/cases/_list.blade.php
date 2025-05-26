<thead>
    <tr>
        <th>Sr.#</th>
        <th>Case Title</th>
        <th>Case Type</th>
        <th>Judge Name</th>
    </tr>
</thead>
<tbody>
    @forelse ($lawyerCases as $case)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$case->case_title}}</td>
        <td>{{$case->case_title}}</td>
        <td>{{$case->case_title}}</td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center">There are no cases list.</td>
    </tr>
    @endforelse
</tbody>
