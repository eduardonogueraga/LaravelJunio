<tr>
    <td rowspan="2">{{ $project->id }}</td>
    <th scope="row">{{ $project->title }}<span class="status st-{{($project->status)? 'active' : 'inactive' }}"></span></th>
    <td scope="row">{{ $project->budget }} €</td>
    <td scope="row">{{ ($project->status)? 'Terminado' : 'Pendiente' }}</td>
    <td scope="row" style="color: {{(\Carbon\Carbon::parse($project->finish_date)->isFuture())? '#000000' : '#ff0000'}}">{{ $project->finish_date }}</td>
    <td class="text-right">
        <a href="{{route('projects.show', ['project' => $project])}}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-eye"></span></a>
        <a href="{{route('projects.edit', ['project' => $project])}}" class="btn btn-outline-secondary btn-sm"><span class="oi oi-pencil"></span></a>
    </td>
</tr>
<tr class="skills">
    <td colspan="1"><span class="note">Descripcion del proyecto: {{Str::limit($project->about, 60)}}</span></td>
</tr>

<tr class="skills">
    <td colspan="2"><span class="note">Equipo principal:
    @forelse($project->teams->filter(function($field) {return $field->pivot->is_head_team == 1;}) as $team)
        <b>{{$team->name}}</b>
            @empty
        <b>Sin equipo principal</b>
    @endforelse
        </span>
    </td>
    <td><span class="note">Número de equipos en el proyecto:{{$project->teams->count()}}</span>
    <td><span class="note">Número de usuarios en el proyecto:{{array_sum(array_column($project->teams->toArray(), 'users_count'))}}
        </span>
    </td>
    </td>
</tr>



