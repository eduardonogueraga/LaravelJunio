<tr>
    <td rowspan="2">{{ $profession->id }}</td>
    <th scope="row">{{ $profession->title }}</th>
    <td>{{ $profession->workday }}</td>
    <td>{{ $profession->academic_level }}</td>
    <td>{{ number_format($profession->salary, 0, ',', '.') }}€</td>
    <td>{{ $profession->profiles_count }}</td>
    <td class="text-right">
        <a href="{{ route('profession.show', $profession) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
        <a href="{{ route('profession.edit', $profession) }}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
        @if($profession->profiles_count == 0)
            <form action="{{ route('professions.destroy', $profession->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
            </form>
        @endif
    </td>
</tr>
<tr class="skills">
    <td colspan="1"><span class="note">Años de experiencia: {{ ($profession->experience)?? 'Sin experiencia' }}</span></td>
    <td colspan="1"><span class="note">Idiomas:{{ ($profession->language)? 'Si': 'No'}}</span></td>
    <td colspan="1"><span class="note">Trasnporte:{{ ($profession->vehicle)? 'Disponer de vehiculo propio': 'No'}}</span></td>
</tr>