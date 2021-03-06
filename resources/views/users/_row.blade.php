<tr>
    <td rowspan="2">{{ $user->id }}</td>
    <th scope="row">
        {{ $user->name }} {{ $user->status }}
        @if ($user->role != 'user')
            ({{ $user->role }})
        @endif
        <span class="status st-{{ $user->state }}"></span>
        <span class="note"><a style="color: #d33625" href="{{route('teams.show',  ['team' =>  intval($user->team->id)])}}">{{ $user->team->name }}</a></span>
        <span class="note">{{ $user->address->country->name }}</span>
    </th>
    <th scope="row">{{ $user->last }}</th>
    <td>{{ ($user->profile->twitter) ?: 'Sin cuenta de twitter' }}</td>
    <td>{{ $user->email }}</td>
    <td>
        <span class="note">{{ $user->created_at->format('d/m/Y h:ia') }}</span>
    </td>
    <td>
        <span class="note">{{ optional($user->last_login_at)->format('d/m/Y h:ia') ?: 'N/A' }}</span>
    </td>
    <td class="text-right">
        @if ($user->trashed())
            <form action="{{ route('users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <a href="{{ route('users.restore', $user) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-action-undo"></span></a>
                <button type="submit" class="btn btn-link"><span class="oi oi-circle-x"></span></button>
            </form>
        @else
            <form action="{{ route('users.trash', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
                <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
            </form>
        @endif
    </td>
</tr>
<tr class="skills">

    <td colspan="3"><span class="note">
            @if($user->profile->profession)
                <a style="color: #2a2730" href="{{route('profession.show',  ['profession' =>  intval($user->profile->profession->id)])}}">{{ $user->profile->profession->title }}</a>
            @else
               (Sin profession)
            @endif
        </span></td>
    <td colspan="4"><span class="note">{{ $user->skills->implode('name', ', ') ?: 'Sin habilidades'}}</span></td>
</tr>
