@extends('layouts.app')

@section('meta_title', "Mise à disposition des données")

@section('breadcrumb')
    <li>
        <span>Outils</span>
        <span class="chevron">></span>
    </li>
    <li>
        <span>Mise à disposition des données</span>
    </li>
@endsection

@section('content')
    <div class="page-content">
        <h2>Mise à disposition des données</h2>
        <div class="content">
            <div class="page-item">
                <div class="page-header">
                  <h3>Liste des flux mis à disposition</h3>
                </div>
                <div class="item-content">
                  <ul>
                      @php($feeds = config('feed.feeds'))

                      @foreach(collect($feeds)->sortBy('title') as $feed)
                          <li><a href="{{ $feed['url'] }}" target="_blank">{{ $feed['title'] }}</a></li>
                      @endforeach
                  </ul>
                </div>
            </div>

            <div class="page-item">
                <div class="page-header">
                  <h3>Exports de la base de données</h3>
                </div>
                <div class="item-content">
                  <p>Cette base de données est mise à disposition sous une <a href="https://www.etalab.gouv.fr/wp-content/uploads/2017/04/ETALAB-Licence-Ouverte-v2.0.pdf">Licence Ouverte 2.0</a> : vous êtes libre de réutiliser les informations qu’elle contient comme bon vous semble, dans la mesure où vous indiquez qu’elles proviennent de la DREAL Nouvelle-Aquitaine et la date à laquelle vous y avez accédé pour la dernière fois.</p>
                  <img src="https://www.etalab.gouv.fr/wp-content/uploads/2011/10/licence-ouverte-open-licence.gif" alt="Licence ouverte"/>
                  <ul>
                    <li><a href="/files/export.csv" target="_blank">Export CSV</a></li>
                  </ul>
                </div>
                <div class="item-content">
                    <table>
                        <caption>Format de la base de données</caption>
                        <thead>
                            <tr>
                                <th>Nom de colonne</th>
                                <th>Valeur représentée</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($feeds = config('feed.feeds'))

                            @foreach(App\Exports\DispositifsExport->$columnsWithDescriptions as $column => $description)
                            <tr>
                                <td>{{ $column }}</td>
                                <td>{{ $description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
