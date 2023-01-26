<div class='bg-white rounded shadow-sm p-4 py-4 d-flex flex-column'>
    <div class='form-group'>
        <label class="form-label">Name</label>
        <p>{{ $user->name }}</p>
    </div>

    <div class='form-group'>
        <label class="form-label">Email</label>
        <p>{{ $user->email }}</p>
    </div>

    <div class='form-group'>
        <label class="form-label">Contact Number</label>
        @if ($user->contact_number)
            <p>{{ $user->contact_number }}</p>
        @else
            <p class="text-muted">No contact number</p>
        @endif
    </div>
</div>