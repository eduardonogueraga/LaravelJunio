<tr>
    <td rowspan="2">{{ $team->id }}</td>
    <th scope="row">{{ $team->name }}</th>
    <td scope="row">{{ $team->users_count }}</td>
    <td scope="row">{{ $team->professions_count }}</td>
    <td class="text-right">
        <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
        <a href="{{ route('teams.edit', $team)}} " class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
   {{--     @if($profession->profiles_count == 0)
            <form action="{{ route('professions.destroy', $profession->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
            </form>
        @endif--}}
    </td>
</tr>
<tr class="skills">
    <td colspan="1"><span class="note">Otra info</span></td>
</tr>

