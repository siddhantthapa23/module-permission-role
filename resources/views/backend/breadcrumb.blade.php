<div class="row">
    <div class="col-md-12">
        <ul class="breadcrumb" style="background:white;">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                @foreach($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb == end($breadcrumbs))
                        <li>
                            {!!  ucwords(str_replace('-', ' ', $breadcrumb['name'])) !!}
                        </li>
                    @else
                        <li>
                            <a href="{{ (array_key_exists('slug', $breadcrumb)) ? route($breadcrumb['slug']) : '#' }}">{!!  ucwords(str_replace('-', ' ', $breadcrumb['name'])) !!}</a>
                        </li>
                    @endif
                @endforeach
        </ul>
    </div>
</div>
        