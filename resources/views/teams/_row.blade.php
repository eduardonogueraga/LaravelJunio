<tr>
    <td rowspan="2">{{ $team->id }}</td>
    <th scope="row">{{ $team->name }}</th>
    <td scope="row">{{ $team->users_count }}</td>
    <td scope="row">{{ $team->professions_count }}</td>
    <td class="text-right">

        @if($team->trashed())
            <form action="{{ route('teams.destroy', $team->id) }}" method="post">
                @csrf
                @method('DELETE')
                <a href="{{ route('teams.restore', $team) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-action-undo"></span></a>
                <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
            </form>
        @elseif($team->users_count == 0)
            <form action="{{ route('teams.trash', $team) }}" method="POST">
                @csrf
                @method('PATCH')
                <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
                <a href="{{ route('teams.edit', $team)}} " class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
                <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
            </form>
        @else
            <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
            <a href="{{ route('teams.edit', $team)}} " class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
        @endif
    </td>
</tr>
<tr class="skills">
    <td colspan="1"><span class="note">Otra info</span></td>
</tr>

