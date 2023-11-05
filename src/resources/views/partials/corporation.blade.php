@if(\Seat\Eveapi\Models\Corporation\CorporationInfo::find($corporationStructure->corporation_id))
    <a href="{{ route('seatcore::corporation.view.default', ['corporation' => $corporationStructure->corporation_id]) }}">
        {!! img('corporations', 'logo', $corporationStructure->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
        {{ $corporationStructure->corporation_name }}
    </a>
@else
    <span>
      {!! img('corporations', 'logo', $corporationStructure->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
        {{ $corporationStructure->corporation_name }}
    </span>
@endif