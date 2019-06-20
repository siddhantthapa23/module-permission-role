@if (session('success_message'))
    <div class="alert alert-success alert-block fade in">
        <button class="close close-sm" data-dismiss="alert" type="button">
            <i class="fa fa-times">
            </i>
        </button>
        <p>
            <h4>
                <i class="icon-ok-sign">
                </i>
                Success!
            </h4>{{ session('success_message') }}
        </p>
    </div>
@endif

@if (session('warning_message'))
    <div class="alert alert-block alert-warning fade in">
        <button class="close close-sm" data-dismiss="alert" type="button">
            <i class="fa fa-times">
            </i>
        </button>
        <h4>
            <i class="icon fa fa-exclamation-triangle">
            </i>
            Warning!
        </h4>
        {{ session('warning_message') }}
    </div>
@endif

@if (session('error_message'))
    <div class="alert alert-block alert-danger fade in">
        <button class="close close-sm" data-dismiss="alert" type="button">
                <span aria-hidden="true">×</span>
        </button>
        <h4>
            <i class="icon fa fa-ban">
            </i>
            Error!
        </h4>
        {{ session('error_message') }}
    </div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-block alert-danger fade in">
        <button class="close close-sm" data-dismiss="alert" type="button">
            <i class="fa fa-times">
            </i>
        </button>
        <h4>
            <i class="icon fa fa-ban">
            </i>
            Error!
        </h4>
        <ul>
            @foreach ($errors->all() as $error)
            <li>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
@endif