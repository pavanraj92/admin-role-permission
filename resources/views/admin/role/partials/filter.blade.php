<div class="card card-body">
    <h4 class="card-title">Filter</h4>
    <form action="{{ route('admin.roles.index') }}" method="GET" id="filterForm">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="title">Keyword</label>
                    <input type="text" name="keyword" id="keyword" class="form-control"
                        value="{{ app('request')->query('keyword') }}" placeholder="Enter name">
                </div>
            </div>
            <div class="col-auto mt-1 text-right">
                <div class="form-group">
                    <label for="created_at">&nbsp;</label>
                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-4">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>