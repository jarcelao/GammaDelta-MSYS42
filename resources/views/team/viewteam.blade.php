<div class='bg-white rounded shadow-sm p-4 py-4 d-flex flex-column'>
    @if ($community->team)
        <div class='form-group'>
            <label class="form-label">Team Leader</label>
            <p>{{ $community->team->team_leader }}</p>
        </div>

        <div class='form-group'>
            <label class="form-label">Team Members</label>
            <ul>
                @foreach ($community->team->teamMembers as $teamMember)
                    <li>{{ $teamMember->name }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="text-muted">No team created.</p>
    @endif
</div>