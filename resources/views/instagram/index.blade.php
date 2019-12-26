@extends('layouts.template')
@section('content')
    @include('layouts.breadcrumb')
    <div class="bg-white p-4">
        <form action="{{ route('json.get') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-10 mb-3">
                    <label for="validationDefaultUsername">Site Url</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend2">
                            <img src="{{ url('svg/bootstrap-icons/folder-symlink.svg') }}" class="icon-width-xs"
                                 alt="menu-icon" title="icon">
                        </span>
                        </div>
                        <input type="text" class="form-control" id="uri" name="uri" required
                               value="https://tr.investing.com/commodities/softs">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="method">Metot</label>
                    <select class="custom-select" id="method" name="method" required>
                        <option value="GET" selected>GET</option>
                        <option value="POST">POST</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="html">Tablo isimlerinin bulunduğu <code>html tag</code></label>
                    <input type="text" class="form-control" id="html"
                           placeholder="örnek:#table_id thead tr th" required name="html_tag1"
                           value="#cross_rate_1 thead tr th">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="html">Tablo içeriğinin bulunduğu <code>html tag</code></label>
                    <input type="text" class="form-control" id="html"
                           placeholder="örnek:#table_id tbody tr td" required name="html_tag2"
                           value="#cross_rate_1 tbody tr td">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                <svg class="bi bi-cloud-download" xmlns="http://www.w3.org/2000/svg">
                    <path/>
                    <path/>
                    <path/>
                </svg>
                İçeriği Getir
            </button>

        </form>
        @isset($result)
            <form action="{{ route('json.download') }}" method="post" class="my-3">
                {{ csrf_field() }}
                <input type="hidden" name="json-data" value="{{ json_encode($result,JSON_PRETTY_PRINT) }}">
                <button type="submit" class="btn btn-warning">Json Dosya Oluştur</button>
            </form>
        @endisset
    </div>
    <div class="bg-white py-4">
        @isset($result)
            @dump($result)
        @endisset
    </div>
@endsection
@section('css')
@endsection
@section('js')
@endsection
