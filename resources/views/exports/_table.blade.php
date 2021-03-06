<table>
    <thead>
    <tr>
        <th>Sous-thématique</th>
        <th>Intitulé</th>
        <th>Date de clôture</th>
        <th>Financeurs des aides</th>
        <th>Localisations</th>
        <th>Objectifs</th>
        <th>Vous êtes ?</th>
        <th>Dotation</th>
        <th>Relais technique DREAL / DDTMs</th>
        <th>Contact(s) porteur de projet</th>
        <th>Lien vers le site</th>
    </tr>
    </thead>
    <tbody>
    @foreach($callsForProjects as $callForProjects)
        @php
            $subthematic = is_null($callForProjects->subthematic) ? null : $callForProjects->subthematic;
            $allocations = [];
            if(!empty($callForProjects->allocation_global)) {
                $allocations[] = 'Dotation globale';
            }
            if(!empty($callForProjects->allocation_per_project)) {
                $allocations[] = 'Dotation par projet';
            }
        @endphp
        <tr class="{{ in_array($callForProjects->id, $callsOfTheWeek->toArray()) ? 'selected' : '' }}">
            <td width="25" valign="top" svalign="top" tyle="wrap-text: true;">{{ empty($subthematic) ? '' : $subthematic->name }}</td>
            <td width="50" valign="top" style="wrap-text: true;">{{ $callForProjects->name }}</td>
            <td width="25" valign="top" style="wrap-text: true;">{{ empty($callForProjects->closing_date) ? '' : $callForProjects->closing_date->format('d/m/Y') }}</td>
            <td width="25" valign="top" style="wrap-text: true;">{!! $callForProjects->projectHolders->pluck('name')->implode('<br>') !!}</td>
            <td width="25" valign="top" style="wrap-text: true;">{!! $callForProjects->perimeters->pluck('name')->implode('<br>') !!}</td>
            <td width="80" valign="top" style="wrap-text: true;">{!! nl2br($callForProjects->objectives) !!}</td>
            <td width="80" valign="top"
                style="wrap-text: true;">{!! $callForProjects->beneficiaries->pluck('name_complete')->implode('<br>') !!}{!! nl2br($callForProjects->beneficiary_comments) !!}</td>
            <td width="80" valign="top" style="wrap-text: true;">
                {!! implode('<br>', $allocations) !!}
                @if(!empty($callForProjects->allocation_amount))
                    <br><br>
                    {{ $callForProjects->allocation_amount }}
                @endif
                @if(!empty($callForProjects->allocation_comments))
                    <br><br>
                    {!! nl2br($callForProjects->allocation_comments) !!}
                @endif
            </td>
            <td width="80" valign="top" style="wrap-text: true;">{!! nl2br($callForProjects->technical_relay) !!}</td>
            <td width="80" valign="top" style="wrap-text: true;">{!! nl2br($callForProjects->project_holder_contact) !!}</td>
            <td width="30" valign="top" style="wrap-text: true; color: blue; text-decoration: underline;">
                @if($type == 'pdf')
                    <a href="{{ $callForProjects->website_url }}" target="_blank">
                        Lien vers le site
                    </a>
                @else
                    {{ $callForProjects->website_url }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>